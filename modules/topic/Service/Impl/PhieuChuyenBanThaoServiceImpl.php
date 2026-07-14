<?php
namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\Topic\Service\PhieuChuyenBanThaoService;
use Modules\Topic\Repository\PhieuChuyenBanThaoRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\Topic\Model\DM_PHIEU_CHUYEN_BAN_THAO;
use Modules\Topic\Object\CongDoanMa;
use Modules\Topic\Object\FilterPhieuChuyenBanThao;
use Modules\Topic\Service\CT_Detai_CongDoanService;

class PhieuChuyenBanThaoServiceImpl extends BaseService implements PhieuChuyenBanThaoService
{
    /** @var PhieuChuyenBanThaoRepository */
    protected $baseRepo;

    public function __construct(PhieuChuyenBanThaoRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterPhieuChuyenBanThao $filter, string $page = 'page-1'): array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 15,
            "page" => $page,
            "loadRelations" => $filter->relations ?? ['sach', 'donvi'],
        ]);

        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getList(FilterPhieuChuyenBanThao $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_PHIEU_CHUYEN_BAN_THAO {
        $isCreate = data_get($data, "id", 0) == 0;

        if(!$isCreate) {
            /** @var DM_PHIEU_CHUYEN_BAN_THAO $phieuChuyenBanThao */
            $phieuChuyenBanThao = $this->baseRepo->get($data["id"]);
            if($phieuChuyenBanThao) {
                $phieuChuyenBanThao->update($data);
                $phieuChuyenBanThao->load(['sach', 'donvi', 'nguoiKy']);
                return $phieuChuyenBanThao;
            }
        }
        /** @var DM_PHIEU_CHUYEN_BAN_THAO $phieuChuyenBanThao */
        $phieuChuyenBanThao = $this->baseRepo->create($data);
        if(!$phieuChuyenBanThao){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        $phieuChuyenBanThao->load(['sach', 'donvi', 'nguoiKy']);

        if ($isCreate) {
            $idDeTai = (int) ($phieuChuyenBanThao->ID_DeTai ?? 0);
            $idCanBo = (int) ($data['CreatedBy'] ?? Auth::id() ?? 0);
            if ($idDeTai > 0 && $idCanBo > 0) {
                /** @var CT_Detai_CongDoanService $congDoanService */
                $congDoanService = app(CT_Detai_CongDoanService::class);
                $congDoanService->ghiCongDoanTheoMaCD(
                    $idDeTai,
                    $idCanBo,
                    CongDoanMa::TAO_PHIEU_CHUYEN_BT,
                    (int) ($phieuChuyenBanThao->ID_Sach ?? 0) ?: null
                );
            }
        }

        return $phieuChuyenBanThao;
    }

    public function delete(int $id): bool {
        $phieuChuyenBanThao = $this->baseRepo->get($id);
        if(!$phieuChuyenBanThao){
            throw new Exception("Phiếu chuyển bản thảo không tồn tại");
        }

        $idDeTai = (int) ($phieuChuyenBanThao->ID_DeTai ?? 0);
        $idSach = (int) ($phieuChuyenBanThao->ID_Sach ?? 0);

        $phieuChuyenBanThao->IsDeleted = true;
        $saved = $phieuChuyenBanThao->save();

        if ($saved && $idDeTai > 0) {
            $idCanBo = (int) (Auth::id() ?? 0);
            if ($idCanBo > 0) {
                /** @var CT_Detai_CongDoanService $congDoanService */
                $congDoanService = app(CT_Detai_CongDoanService::class);
                $congDoanService->ghiCongDoanTheoMaCD(
                    $idDeTai,
                    $idCanBo,
                    CongDoanMa::XOA_PHIEU_CHUYEN_BT,
                    $idSach > 0 ? $idSach : null
                );
            }
        }

        return $saved;
    }
}
