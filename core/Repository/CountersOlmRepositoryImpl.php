<?php
namespace Core\Repository;

use App\Exceptions\RemoteException;
use Core\Model\CountersOlm;
use Core\Repository\BaseRepository;
use Core\Repository\CountersOlmRepository;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CountersOlmRepositoryImpl extends BaseRepository implements CountersOlmRepository {
	public function getModel() {
        return CountersOlm::class;
    }

    public function increment($_id, $randInc = true) {
        // Luôn đồng bộ counter theo _id lớn nhất trước khi cấp số mới.
        $this->syncCounterToCollectionMaxId((string)$_id);

        /** @var CountersOlm|null $counter */
        $counter = $this->_model::where("_id", $_id)->first();
        if (!$counter) {
            // Trường hợp hiếm: chưa có counter sau khi sync, tạo mới từ 0 rồi tăng lên 1.
            $counter = $this->_model::create(["_id" => $_id, "seq" => 0]);
        }

        $counter->increment("seq", 1);
        $counter->refresh();
        $seq = (int)($counter->seq ?? 0);

        if ($randInc) {
            $inc = rand(100, 999);
            return (int)($seq . $inc);
        }

		return $seq;
	}

    public function retry($_id, $try, $maxTries = 10) {
        for ($i = 0; $i < $maxTries; $i++) {
            try {
                // Luôn cấp id mới mỗi lần thử lại để tránh lặp dup key cùng một id.
                $id = $this->increment($_id, false);
                return $try($id);
            } catch (\Throwable $e) {
                if ((int)$e->getCode() !== 11000) {
                    throw $e;
                }
                // Nếu counter bị lệch so với dữ liệu thật, tự đồng bộ lại rồi retry.
                $this->syncCounterToCollectionMaxId($_id);
            }
        }

        if (isset($e)) {
            throw $e;
        }
        throw new InvalidArgumentException("Không thể tạo id mới sau nhiều lần thử", 500);
    }

    /**
     * Đồng bộ seq của counter theo _id lớn nhất hiện có của collection.
     * Dùng khi gặp duplicate key để tự "nhảy cóc" qua vùng id đã tồn tại.
     */
    protected function syncCounterToCollectionMaxId(string $collection): void
    {
        $maxId = $this->getCollectionMaxId($collection);
        /** @var CountersOlm|null $counter */
        $counter = $this->_model::where("_id", $collection)->first();
        if (!$counter) {
            $this->_model::create(["_id" => $collection, "seq" => $maxId]);
            return;
        }

        // Chỉ tăng tiến, không hạ seq xuống để tránh quay lại id cũ.
        if ((int)($counter->seq ?? 0) < $maxId) {
            $counter->update(["seq" => $maxId]);
        }
    }

    protected function getCollectionMaxId(string $collection): int
    {
        $maxId = DB::connection(config("database.mongo_primary_connection", "olm_primary"))
            ->table($collection)
            ->where("_id", ">=", 0)
            ->max("_id");
        return is_numeric($maxId) ? (int)$maxId : 0;
    }

}
