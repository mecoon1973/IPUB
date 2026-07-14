<?php

namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;
use Core\Service\BaseService;
use Exception;
use Modules\Book\Model\DM_SACH;
use Modules\Book\Repository\SachRepository;
use Modules\Book\Service\SachService;
use Modules\System\Object\FilterDonvi;
use Modules\System\Service\DonviService;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\FilterHDXBNXBGDVN;
use Modules\Topic\Object\FilterPheDuyetDiIn;
use Modules\Topic\Object\TrangThaiDocBanThao;
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

    public function getPaginatePheDuyetDiIn(FilterPheDuyetDiIn $filter, string $page = 'page-1'): array {
        /** @var SachService $sachService */
        $sachService = app(SachService::class);
        $conditions = $this->buildPheDuyetDiInConditions($filter);
        $result = $sachService->paginateWithConditions($conditions, $page, 100);
        $items = is_object($result['listResult']) && method_exists($result['listResult'], 'all')
            ? $result['listResult']->all()
            : (array) $result['listResult'];

        return [
            'listResult' => $this->enrichPheDuyetDiInItems($items),
            'pagiInfo' => $result['pagiInfo'],
        ];
    }

    /** @param array<int, array<string, mixed>> $items */
    public function luuPheDuyetDiIn(array $items, int $idCanBo): int {
        if (count($items) === 0) {
            throw new Exception('Vui lòng chọn ít nhất một sách');
        }

        /** @var SachRepository $sachRepo */
        $sachRepo = app(SachRepository::class);

        $count = 0;
        foreach ($items as $item) {
            $idSach = (int) ($item['id'] ?? 0);
            if ($idSach <= 0) {
                continue;
            }

            /** @var DM_SACH|null $sach */
            $sach = $sachRepo->get($idSach);
            if (!$sach || (bool) ($sach->IsDeleted ?? false)) {
                continue;
            }

            $sach->YKienDocBanThao = (string) ($item['YKienDocBanThao'] ?? '');
            $sach->XetDuyetBanThao = (bool) ($item['XetDuyetBanThao'] ?? false);

            if ($sach->save()) {
                $count++;
            }
        }

        if ($count === 0) {
            throw new Exception('Không có sách nào được lưu');
        }

        return $count;
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
        $phanCong = $filter->PhanCong;

        if ($phanCong === null || $phanCong === FilterHDXBNXBGDVN::PHAN_CONG_TAT_CA) {
            return $conditions;
        }

        /** @var NX_CanboDetaiService $nxCanboDetaiService */
        $nxCanboDetaiService = app(NX_CanboDetaiService::class);

        if ($phanCong === FilterHDXBNXBGDVN::PHAN_CONG_DA_CA_NHAN) {
            $idCanBo = (int) (\Illuminate\Support\Facades\Auth::user()->_id ?? 0);
            $idsDaPhanCong = $idCanBo > 0
                ? $nxCanboDetaiService->getActivePhanCongDeTaiIdsByCanBo($idCanBo)
                : [];
            $phanCongCondition = [
                '_id' => ['$in' => count($idsDaPhanCong) > 0 ? $idsDaPhanCong : [0]],
            ];
        } elseif ($phanCong === FilterHDXBNXBGDVN::PHAN_CONG_DA_TAT_CA) {
            $idsDaPhanCong = $nxCanboDetaiService->getActivePhanCongDeTaiIds();
            $phanCongCondition = [
                '_id' => ['$in' => count($idsDaPhanCong) > 0 ? $idsDaPhanCong : [0]],
            ];
        } else {
            $idsDaPhanCong = $nxCanboDetaiService->getActivePhanCongDeTaiIds();
            $phanCongCondition = ['_id' => ['$nin' => $idsDaPhanCong]];
        }

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

    private function buildPheDuyetDiInConditions(FilterPheDuyetDiIn $filter): array {
        $conditions = $filter->buildConditions();

        if ($filter->LocTheo !== 1) {
            return $conditions;
        }

        /** @var NX_CanboDetaiService $nxCanboDetaiService */
        $nxCanboDetaiService = app(NX_CanboDetaiService::class);
        $idsDaPhanCong = $nxCanboDetaiService->getActivePhanCongDeTaiIds();
        $phanCongCondition = [
            'ID_DeTai' => ['$in' => count($idsDaPhanCong) > 0 ? $idsDaPhanCong : [0]],
        ];

        if (isset($conditions['$and'])) {
            $conditions['$and'][] = $phanCongCondition;
            return $conditions;
        }

        return ['$and' => [$conditions, $phanCongCondition]];
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

    /**
     * @param DM_SACH[] $items
     * @return array<int, array<string, mixed>>
     */
    private function enrichPheDuyetDiInItems(array $items): array {
        if (count($items) === 0) {
            return [];
        }

        $donviMap = $this->getDonviMap();
        $result = [];

        foreach ($items as $item) {
            $xetDuyetBanThao = (bool) ($item->XetDuyetBanThao ?? false);
            $trangThaiDocBanThao = (int) ($item->TrangThaiDocBanThao ?? TrangThaiDocBanThao::CHUA_DOC_DUYET);
            $result[] = [
                'id' => (int) $item->_id,
                'ID_DeTai' => (int) ($item->ID_DeTai ?? 0),
                'MaSo' => (string) ($item->MaSo ?? ''),
                'TenSach' => (string) ($item->TenSach ?? ''),
                'NamTaiBan' => (string) ($item->NamTaiBan ?? ''),
                'NamXuatBan' => (string) ($item->NamXuatBan ?? ''),
                'TenDonVi' => $donviMap[(int) ($item->ID_DonVi ?? 0)] ?? '',
                'TrangThaiDocBanThao' => $trangThaiDocBanThao,
                'YKienDocBanThao' => (string) ($item->YKienDocBanThao ?? ''),
                'XetDuyetBanThao' => $xetDuyetBanThao,
                'DaPheDuyetDiIn' => $xetDuyetBanThao,
                'TenTrangThai' => TrangThaiDocBanThao::label($trangThaiDocBanThao),
            ];
        }

        return $result;
    }
}
