<?php

namespace Modules\Topic\Service\Impl;

use Core\Service\BaseService;
use Exception;
use Modules\Topic\Model\NX_CANBO_DETAI;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\FilterXetDuyetHDXBNXBGDVN;
use Modules\Topic\Object\NxCanBoDetaiDuyet;
use Modules\Topic\Object\PhieuDkDetaiTrangThai;
use Modules\Topic\Repository\NX_CanboDetaiRepository;
use Modules\Topic\Repository\PhieuDkDetaiRepository;
use Modules\Topic\Service\CT_Detai_CongDoanService;
use Modules\Topic\Service\NX_CanboDetaiService;
use Modules\User\Model\User;
use Modules\User\Service\UserService;

class NX_CanboDetaiServiceImpl extends BaseService implements NX_CanboDetaiService
{
    /** @var NX_CanboDetaiRepository */
    protected $baseRepo;

    public function __construct(NX_CanboDetaiRepository $baseRepo)
    {
        parent::__construct($baseRepo);
    }

    public function getActivePhanCongMapByDeTaiIds(array $idsDeTai): array
    {
        $idsDeTai = array_values(array_filter(array_map('intval', $idsDeTai)));
        if (count($idsDeTai) === 0) {
            return [];
        }

        $rows = $this->baseRepo->findAll([
            'ID_DeTai' => ['$in' => $idsDeTai],
            'LaPhanCong' => true,
            'IsDeleted' => false,
            'InUsed' => true,
        ]);

        $map = [];
        foreach ($rows as $row) {
            $idDeTai = (int) ($row->ID_DeTai ?? 0);
            if ($idDeTai > 0) {
                $map[$idDeTai] = $row;
            }
        }

        return $map;
    }

    public function getActivePhanCongDeTaiIds(): array
    {
        return $this->baseRepo->getActivePhanCongDeTaiIds();
    }

    public function phanCongDocDuyet(array $idsDeTai, int $idCanBoDoc, int $idCanBoPhanCong): int
    {
        /** @var CT_Detai_CongDoanService $congDoanService */
        $congDoanService = app(CT_Detai_CongDoanService::class);

        if ($idCanBoDoc <= 0) {
            throw new Exception('Vui lòng chọn cán bộ phân công đọc duyệt');
        }
        if ($idCanBoPhanCong <= 0) {
            throw new Exception('Người dùng chưa đăng nhập');
        }
        if (count($idsDeTai) === 0) {
            throw new Exception('Vui lòng chọn ít nhất một đề tài');
        }

        /** @var UserService $userService */
        $userService = app(UserService::class);
        /** @var User|null $canBoDoc */
        $canBoDoc = $userService->findOne('no-cache', ['id' => $idCanBoDoc]);
        if (!$canBoDoc) {
            throw new Exception('Cán bộ được phân công không tồn tại');
        }

        /** @var PhieuDkDetaiRepository $phieuRepo */
        $phieuRepo = app(PhieuDkDetaiRepository::class);
        $allowedTrangThai = [
            PhieuDkDetaiTrangThai::HDXB_DON_VI_PHE_DUYET,
            PhieuDkDetaiTrangThai::HDXB_NXBGDVN_TRA_LAI,
            PhieuDkDetaiTrangThai::HDXB_NXBGDVN_DANG_XET,
            PhieuDkDetaiTrangThai::HDXB_NXBGDVN_PHE_DUYET,
            PhieuDkDetaiTrangThai::HDXB_NXBGDVN_CHUA_XET,
        ];

        $count = 0;
        $now = now();

        foreach ($idsDeTai as $idDeTai) {
            /** @var PHIEU_DK_DETAI|null $phieu */
            $phieu = $phieuRepo->get((int) $idDeTai);
            if (!$phieu) {
                continue;
            }

            $trangThai = (int) ($phieu->TrangThai ?? 0);
            if (!in_array($trangThai, $allowedTrangThai, true)) {
                throw new Exception("Đề tài [{$phieu->TenDeTai}] không ở trạng thái cho phép phân công đọc duyệt");
            }

            $this->deactivatePhanCongCu((int) $phieu->_id, $idCanBoPhanCong, $now);

            /** @var NX_CANBO_DETAI $nxCanBoDetai */
            $nxCanBoDetai = $this->baseRepo->create([
                'ID_DeTai' => (int) $phieu->_id,
                'ID_CanBo' => $idCanBoDoc,
                'ID_CanBoPhanCong' => $idCanBoPhanCong,
                'MaTrangThai' => $trangThai,
                'Duyet' => 0,
                'NhanXet' => '',
                'LaPhanCong' => true,
                'DaGui' => true,
                'InUsed' => true,
                'IsDeleted' => false,
                'KhoaGuiNhan' => $this->buildKhoaGuiNhan($idCanBoPhanCong),
                'CreatedBy' => $idCanBoPhanCong,
                'CreatedOn' => $now,
                'NgayNX' => $now,
                'EditedBy' => null,
                'EditedOn' => null,
            ]);

            if (!$nxCanBoDetai) {
                throw new Exception('Không thể tạo bản ghi phân công đọc duyệt');
            }

            if ($trangThai === PhieuDkDetaiTrangThai::HDXB_DON_VI_PHE_DUYET
                || $trangThai === PhieuDkDetaiTrangThai::HDXB_NXBGDVN_CHUA_XET) {
                $phieu->TrangThai = PhieuDkDetaiTrangThai::HDXB_NXBGDVN_DANG_XET;
            }
            $phieu->save();

            $congDoanService->ghiCongDoanTrangThai(
                (int) $phieu->_id,
                $idCanBoDoc,
                $trangThai,
                PhieuDkDetaiTrangThai::HDXB_NXBGDVN_DANG_XET
            );

            $count++;
        }

        if ($count === 0) {
            throw new Exception('Không tìm thấy đề tài hợp lệ để phân công');
        }

        return $count;
    }

    public function getListXetDuyet(FilterXetDuyetHDXBNXBGDVN $filter): array
    {
        $conditions = $filter->buildNxPhanCongConditions();
        $nxRows = $this->baseRepo->findAll($conditions);

        if ($nxRows->isEmpty()) {
            return [];
        }

        $phanCongMap = [];
        foreach ($nxRows as $nx) {
            $idDeTai = (int) ($nx->ID_DeTai ?? 0);
            if ($idDeTai > 0 && !isset($phanCongMap[$idDeTai])) {
                $phanCongMap[$idDeTai] = $nx;
            }
        }

        $idsDeTai = array_keys($phanCongMap);
        if (count($idsDeTai) === 0) {
            return [];
        }

        /** @var PhieuDkDetaiRepository $phieuRepo */
        $phieuRepo = app(PhieuDkDetaiRepository::class);
        $conditionsPhieu = $filter->buildPhieuConditions($idsDeTai);
        $phieuRows = $phieuRepo->findAll($conditionsPhieu, ['TenDeTai' => 1]);

        $result = [];
        foreach ($phieuRows as $phieu) {
            $idDeTai = (int) ($phieu->_id ?? 0);
            if ($idDeTai <= 0 || !isset($phanCongMap[$idDeTai])) {
                continue;
            }

            /** @var NX_CANBO_DETAI $nx */
            $nx = $phanCongMap[$idDeTai];

            $result[] = [
                'id' => $idDeTai,
                'idNxCanBoDetai' => (int) ($nx->_id ?? 0),
                'TenDeTai' => (string) ($phieu->TenDeTai ?? ''),
                'YKienDocDuyet' => (string) ($nx->NhanXet ?? ''),
                'YKienHDXB' => (string) ($phieu->YKHDDD ?? ''),
                'Duyet' => (int) ($nx->Duyet ?? NxCanBoDetaiDuyet::CHUA_XET),
                'YeuCauDocKiemDinh' => (bool) ($phieu->YeuCauDocKiemDinh ?? false),
            ];
        }

        return $result;
    }

    public function luuXetDuyetDeTai(array $items, int $idCanBo): int
    {
        if ($idCanBo <= 0) {
            throw new Exception('Người dùng chưa đăng nhập');
        }
        if (count($items) === 0) {
            throw new Exception('Không có đề tài để lưu');
        }

        /** @var PhieuDkDetaiRepository $phieuRepo */
        $phieuRepo = app(PhieuDkDetaiRepository::class);
        /** @var CT_Detai_CongDoanService $congDoanService */
        $congDoanService = app(CT_Detai_CongDoanService::class);

        $count = 0;
        $now = now();

        foreach ($items as $item) {
            /** @var NX_CANBO_DETAI|null $nx */
            $nx = $this->baseRepo->get((int) ($item['idNxCanBoDetai'] ?? 0));
            if (!$nx || (int) ($nx->ID_DeTai ?? 0) !== (int) ($item['idDeTai'] ?? 0)) {
                continue;
            }

            /** @var PHIEU_DK_DETAI|null $phieu */
            $phieu = $phieuRepo->get((int) $item['idDeTai']);
            if (!$phieu) {
                continue;
            }

            $trangThaiCu = (int) ($phieu->TrangThai ?? 0);
            $duyet = (int) ($item['Duyet'] ?? NxCanBoDetaiDuyet::CHUA_XET);

            $nx->NhanXet = (string) ($item['YKienDocDuyet'] ?? '');
            $nx->Duyet = $duyet;
            $nx->NgayNX = $now;
            $nx->EditedBy = $idCanBo;
            $nx->EditedOn = $now;
            $nx->save();

            $phieu->YKHDDD = (string) ($item['YKienHDXB'] ?? '');
            $phieu->YeuCauDocKiemDinh = (bool) ($item['YeuCauDocKiemDinh'] ?? false);
            $phieu->EditedBy = $idCanBo;
            $phieu->EditedOn = $now;

            if ($duyet === NxCanBoDetaiDuyet::DUYET) {
                $phieu->TrangThai = PhieuDkDetaiTrangThai::HDXB_NXBGDVN_PHE_DUYET;
            } elseif ($duyet === NxCanBoDetaiDuyet::TRA_LAI) {
                $phieu->TrangThai = PhieuDkDetaiTrangThai::HDXB_NXBGDVN_TRA_LAI;
            }

            $phieu->save();

            $trangThaiMoi = (int) ($phieu->TrangThai ?? 0);
            if ($trangThaiCu !== $trangThaiMoi) {
                $congDoanService->ghiCongDoanTrangThai(
                    (int) $phieu->_id,
                    $idCanBo,
                    $trangThaiCu,
                    $trangThaiMoi
                );
            }

            $count++;
        }

        if ($count === 0) {
            throw new Exception('Không tìm thấy đề tài hợp lệ để lưu');
        }

        return $count;
    }

    private function deactivatePhanCongCu(int $idDeTai, int $idCanBoPhanCong, $now): void
    {
        $this->baseRepo->update(
            [
                'ID_DeTai' => $idDeTai,
                'LaPhanCong' => true,
                'IsDeleted' => false,
                'InUsed' => true,
            ],
            [
                'InUsed' => false,
                'IsDeleted' => true,
                'EditedBy' => $idCanBoPhanCong,
                'EditedOn' => $now,
            ]
        );
    }

    private function buildKhoaGuiNhan(int $userId): string
    {
        $now = now();
        $ms = (int) floor((microtime(true) - floor(microtime(true))) * 1000);

        return sprintf(
            '%d_%dh%dm%ds%dms_%d_%d_%d_%s',
            $userId,
            (int) $now->format('G'),
            (int) $now->format('i'),
            (int) $now->format('s'),
            $ms,
            (int) $now->format('j'),
            (int) $now->format('n'),
            (int) $now->format('Y'),
            str_replace('.', '', (string) microtime(true))
        );
    }
}
