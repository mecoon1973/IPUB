<?php
namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\Topic\Service\CT_Detai_CongDoanService;
use Modules\Topic\Repository\CT_Detai_CongDoanRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Object\FilterTrangThai;
use Modules\System\Service\TrangThaiService;
use Modules\Topic\Model\CT_Detai_Congdoan;
use Modules\Topic\Model\DM_CONGDOAN;
use Modules\Topic\Object\CongDoanMa;
use Modules\Topic\Object\FilterCT_Detai_Congdoan;

class CT_Detai_CongDoanServiceImpl extends BaseService implements CT_Detai_CongDoanService
{
    /** @var CT_Detai_CongDoanRepository */
    protected $baseRepo;

    public function __construct(CT_Detai_CongDoanRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterCT_Detai_Congdoan $filter, string $page = 'page-1'): array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 10,
            "page" => $page,
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getList(FilterCT_Detai_Congdoan $filter) {
        return $this->baseRepo->findAllWithFilter($filter);
    }

    public function store(array $data): CT_Detai_Congdoan {
        if(data_get($data, "id", 0) != 0) {
            /** @var CT_Detai_Congdoan $phieuDkDetai */
            $ct_detai_congdoan = $this->baseRepo->get($data["id"]);
            if($ct_detai_congdoan) {
                $ct_detai_congdoan->update($data);
                return $ct_detai_congdoan;
            }
        }

        /** @var CT_Detai_Congdoan $ct_detai_congdoan */
        $ct_detai_congdoan = $this->baseRepo->create($data);
        if(!$ct_detai_congdoan){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $ct_detai_congdoan;
    }

    public function ghiCongDoanTrangThai(int $idDeTai, int $idCanBo, int $trangThaiCu, int $trangThaiMoi): CT_Detai_Congdoan {
        /** @var TrangThaiService $trangThaiService */
        $trangThaiService = app(TrangThaiService::class);
        $mapTrangThai = $trangThaiService->getMapTrangThai(new FilterTrangThai(['DaGui' => true]));

        $tenTrangThaiCu = $mapTrangThai[$trangThaiCu] ?? (string) $trangThaiCu;
        $tenTrangThaiMoi = $mapTrangThai[$trangThaiMoi] ?? (string) $trangThaiMoi;

        $macd = CongDoanMa::trangThaiMap()[$trangThaiMoi] ?? '';
        if ($macd === '') {
            $macd = $this->resolveMaCdFromTenTrangThai($tenTrangThaiMoi);
        }

        return $this->ghiCongDoanTheoMaCD(
            $idDeTai,
            $idCanBo,
            $macd,
            null,
            $tenTrangThaiCu . ' → ' . $tenTrangThaiMoi,
            (string) $trangThaiCu,
            (string) $trangThaiMoi,
            $tenTrangThaiMoi
        );
    }

    public function ghiCongDoanTheoMaCD(
        int $idDeTai,
        int $idCanBo,
        string $macd,
        ?int $idSach = null,
        ?string $ghiChu = null,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?string $noiDungFallback = null
    ): CT_Detai_Congdoan {
        if ($idDeTai <= 0) {
            throw new Exception('ID đề tài không hợp lệ');
        }

        $macd = trim($macd);
        $idCongDoan = 0;
        $noiDung = $noiDungFallback ?? $macd;
        $maCdLuu = $macd;

        if ($macd !== '') {
            /** @var DM_CONGDOAN|null $dmCongDoan */
            $dmCongDoan = $this->findDmCongDoanByMaCd($macd);
            if ($dmCongDoan) {
                $idCongDoan = (int) $dmCongDoan->_id;
                $maCdLuu = (string) $dmCongDoan->macd;
                if ((string) $dmCongDoan->tencd !== '') {
                    $noiDung = (string) $dmCongDoan->tencd;
                }
            }
        }

        $payload = [
            'IDDeTai' => $idDeTai,
            'IDCongDoan' => $idCongDoan,
            'MaCD' => $maCdLuu,
            'NoiDung' => $noiDung,
            'CreatedBy' => $idCanBo,
            'CreatedOn' => now(),
            'EditedBy' => $idCanBo,
            'EditedOn' => now(),
        ];

        if ($idSach !== null && $idSach > 0) {
            $payload['IDSach'] = $idSach;
        }
        if ($ghiChu !== null && $ghiChu !== '') {
            $payload['GhiChu'] = $ghiChu;
        }
        if ($oldValue !== null) {
            $payload['OldValue'] = $oldValue;
        }
        if ($newValue !== null) {
            $payload['NewValue'] = $newValue;
        }

        return $this->store($payload);
    }

    private function findDmCongDoanByMaCd(string $macd): ?DM_CONGDOAN
    {
        $macd = trim($macd);
        if ($macd === '') {
            return null;
        }

        $candidates = array_values(array_unique([
            $macd,
            strtoupper($macd),
            preg_match('/^M/i', $macd) ? $macd : 'M' . $macd,
        ]));

        foreach ($candidates as $candidate) {
            /** @var DM_CONGDOAN|null $found */
            $found = DM_CONGDOAN::query()
                ->where('macd', $candidate)
                ->where('inused', true)
                ->first();
            if ($found) {
                return $found;
            }
        }

        return null;
    }

    private function resolveMaCdFromTenTrangThai(string $tenTrangThai): string
    {
        if (preg_match('/^([\d]+(?:\.[\d]+)?)\./', $tenTrangThai, $matches)) {
            $prefix = $matches[1];
            $candidate = 'M' . $prefix;
            if ($this->findDmCongDoanByMaCd($candidate)) {
                return $candidate;
            }
            return $candidate;
        }

        return '';
    }

    public function delete(int $id): bool {
        $ct_detai_congdoan = $this->baseRepo->get($id);
        if(!$ct_detai_congdoan){
            throw new Exception("CT_Detai_Congdoan không tồn tại");
        }
        $ct_detai_congdoan->IsDeleted = true;
        return $ct_detai_congdoan->save();
    }
}
