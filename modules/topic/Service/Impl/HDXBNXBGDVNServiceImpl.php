<?php

namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;
use Core\Service\BaseService;
use Modules\System\Object\FilterDonvi;
use Modules\System\Service\DonviService;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\FilterHDXBNXBGDVN;
use Modules\Topic\Repository\HDXBNXBGDVNRepository;
use Modules\Topic\Service\HDXBNXBGDVNService;
use Modules\Topic\Service\NX_CanboDetaiService;
use Modules\User\Service\UserService;

class HDXBNXBGDVNServiceImpl extends BaseService implements HDXBNXBGDVNService
{
    /** @var HDXBNXBGDVNRepository */
    protected $baseRepo;

    public function __construct(HDXBNXBGDVNRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterHDXBNXBGDVN $filter, string $page = 'page-1'): array {
        $conditions = $this->buildConditionsWithPhanCong($filter);
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 100,
            "page" => $page,
        ]);
        $result = $this->pagination($paginate);

        return [
            "listResult" => $this->enrichListItems($result->list->all()),
            "pagiInfo" => $result->pagi_info,
        ];
    }

    public function getList(FilterHDXBNXBGDVN $filter) {
        $conditions = $this->buildConditionsWithPhanCong($filter);
        $list = $this->baseRepo->findAll($conditions);
        return $this->enrichListItems($list->all());
    }

    /** @param int[] $idsDeTai */
    public function phanCongDocDuyet(array $idsDeTai, int $idCanBo): int {
        /** @var NX_CanboDetaiService $nxCanboDetaiService */
        $nxCanboDetaiService = app(NX_CanboDetaiService::class);
        $idCanBoPhanCong = (int) (\Illuminate\Support\Facades\Auth::user()->_id ?? 0);

        return $nxCanboDetaiService->phanCongDocDuyet($idsDeTai, $idCanBo, $idCanBoPhanCong);
    }

    /**
     * @param PHIEU_DK_DETAI[] $items
     * @return PHIEU_DK_DETAI[]
     */
    private function enrichListItems(array $items): array {
        if (count($items) === 0) {
            return [];
        }

        $idsDeTai = array_map(static fn ($item) => (int) $item->_id, $items);

        /** @var NX_CanboDetaiService $nxCanboDetaiService */
        $nxCanboDetaiService = app(NX_CanboDetaiService::class);
        $phanCongMap = $nxCanboDetaiService->getActivePhanCongMapByDeTaiIds($idsDeTai);

        $canBoIds = [];
        foreach ($phanCongMap as $row) {
            $canBoIds[] = (int) ($row->ID_CanBo ?? 0);
        }
        $canBoTenMap = $this->getCanBoTenMap(array_values(array_unique(array_filter($canBoIds))));

        $donviMap = $this->getDonviMap();

        foreach ($items as $item) {
            $idDeTai = (int) $item->_id;
            $trangThai = (int) ($item->TrangThai ?? 0);
            $item->TenDonVi = $donviMap[(int) ($item->ID_DonVi ?? 0)] ?? '';
            $item->TenTrangThai = $this->resolveTenTrangThaiHienThi($trangThai);

            if (isset($phanCongMap[$idDeTai])) {
                $phanCong = $phanCongMap[$idDeTai];
                $idCanBo = (int) ($phanCong->ID_CanBo ?? 0);
                $item->NguoiDocDuyet = $canBoTenMap[$idCanBo] ?? '';
                $item->ID_CanBoDocDuyet = $idCanBo;
                $item->PhanCong = 'Đã phân công';
                $item->DaPhanCong = true;
            } else {
                $item->NguoiDocDuyet = '';
                $item->ID_CanBoDocDuyet = 0;
                $item->PhanCong = 'Chưa phân công';
                $item->DaPhanCong = false;
            }
        }

        return $items;
    }

    private function buildConditionsWithPhanCong(FilterHDXBNXBGDVN $filter): array {
        $conditions = $filter->buildConditions();

        if ($filter->PhanCong !== 0 && $filter->PhanCong !== 1) {
            return $conditions;
        }

        /** @var NX_CanboDetaiService $nxCanboDetaiService */
        $nxCanboDetaiService = app(NX_CanboDetaiService::class);
        $idsDaPhanCong = $nxCanboDetaiService->getActivePhanCongDeTaiIds();

        $phanCongCondition = $filter->PhanCong === 1
            ? ['_id' => ['$in' => count($idsDaPhanCong) > 0 ? $idsDaPhanCong : [0]]]
            : ['_id' => ['$nin' => $idsDaPhanCong]];

        if (isset($conditions['$and'])) {
            $conditions['$and'][] = $phanCongCondition;
            return $conditions;
        }

        return ['$and' => [$conditions, $phanCongCondition]];
    }

    /** @param int[] $idsCanBo */
    private function getCanBoTenMap(array $idsCanBo): array {
        if (count($idsCanBo) === 0) {
            return [];
        }

        /** @var UserService $userService */
        $userService = app(UserService::class);
        $map = [];
        foreach ($idsCanBo as $idCanBo) {
            $user = $userService->findOne('no-cache', ['id' => $idCanBo]);
            if ($user) {
                $map[$idCanBo] = (string) ($user->HoTen ?? '');
            }
        }

        return $map;
    }

    private $donviMapCache = null;

    private function getDonviMap(): array {
        if ($this->donviMapCache !== null) {
            return $this->donviMapCache;
        }
        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);
        $listDonvi = $donviService->getAllDonvi(new FilterDonvi([
            "IsDeleted" => false,
        ]));

        $map = [];
        foreach ($listDonvi as $donvi) {
            $map[(int) $donvi->id] = (string) ($donvi->TenDonVi ?? "");
        }

        $this->donviMapCache = $map;
        return $map;
    }

    private function resolveTenTrangThaiHienThi(int $trangThai): string
    {
        $map = [
            4 => 'Đề tài bị trả lại',
            5 => 'Đang xử lý',
            6 => 'Đã xử lý',
            16 => 'Đang xử lý',
        ];

        return $map[$trangThai] ?? (string) $trangThai;
    }
}
