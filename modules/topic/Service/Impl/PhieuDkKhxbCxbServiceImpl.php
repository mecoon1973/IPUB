<?php

namespace Modules\Topic\Service\Impl;

use Core\Model\CountersOlm;
use Core\Object\Paginate;
use Core\Service\BaseService;
use Exception;
use MongoDB\BSON\Regex;
use Modules\Topic\Model\PHIEU_DK_KHXB_CXB;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\CongDoanMa;
use Modules\Topic\Object\FilterPhieuDkKhxbCxb;
use Modules\Topic\Object\PhieuDkDetaiTrangThai;
use Modules\Book\Repository\SachRepository;
use Modules\Book\Service\SachService;
use Modules\System\Service\DonviService;
use Modules\Topic\Repository\CT_PhieuDkKhxbCxbRepository;
use Modules\Topic\Repository\PhieuDkDetaiRepository;
use Modules\Topic\Repository\PhieuDkKhxbCxbRepository;
use Modules\Topic\Service\CT_Detai_CongDoanService;
use Modules\Topic\Service\PhieuDkKhxbCxbService;

class PhieuDkKhxbCxbServiceImpl extends BaseService implements PhieuDkKhxbCxbService
{
    private const MA_SO_COUNTER_KEY = 'ma_so_phieu_dk_khxb_cxb';

    private const MA_SO_CXB_COUNTER_KEY = 'ma_so_cxb_iph';

    private const TRANG_THAI_KET_CHUYEN_THANH_SACH = 17;

    /** @var PhieuDkKhxbCxbRepository */
    protected $baseRepo;

    /** @var PhieuDkDetaiRepository */
    protected $phieuDkDetaiRepo;

    /** @var CT_PhieuDkKhxbCxbRepository */
    protected $ctPhieuDkKhxbCxbRepo;

    public function __construct(
        PhieuDkKhxbCxbRepository $baseRepo,
        PhieuDkDetaiRepository $phieuDkDetaiRepo,
        CT_PhieuDkKhxbCxbRepository $ctPhieuDkKhxbCxbRepo
    ) {
        parent::__construct($baseRepo);
        $this->phieuDkDetaiRepo = $phieuDkDetaiRepo;
        $this->ctPhieuDkKhxbCxbRepo = $ctPhieuDkKhxbCxbRepo;
    }

    public function getPaginate(FilterPhieuDkKhxbCxb $filter, string $page = 'page-1'): array
    {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            'conditions' => $conditions,
            'limit' => 10,
            'page' => $page,
        ]);
        $result = $this->pagination($paginate);

        return [
            'listResult' => $result->list,
            'pagiInfo' => $result->pagi_info,
        ];
    }

    public function getList(FilterPhieuDkKhxbCxb $filter)
    {
        return $this->baseRepo->findAllWithFilter($filter);
    }

    public function previewMaSo(): string
    {
        return $this->buildMaSoFromNextSeq($this->resolveNextMaSoSeq(false));
    }

    public function getDetail(int $id): array
    {
        /** @var PHIEU_DK_KHXB_CXB|null $phieu */
        $phieu = $this->baseRepo->get($id);
        if (!$phieu || $phieu->IsDeleted) {
            throw new Exception('Phiếu trình CXB không tồn tại');
        }

        $listIdDeTai = $this->loadListIdDeTaiFromChiTiet($id);

        return [
            'phieu' => $phieu,
            'listDeTai' => $this->loadDeTaiByIds($listIdDeTai),
        ];
    }

    public function previewMaSoCxbSeq(): int
    {
        return $this->resolveNextCxbSeq(false);
    }

    /**
     * Cấp mã số CXB cho các đề tài thuộc phiếu trình.
     * Ghép mã hoàn chỉnh và lưu vào PHIEU_DK_DETAI.MaSoCXB.
     */
    public function capMaSoCxb(int $idPhieu, array $data, int $idCanBo): array
    {
        /** @var PHIEU_DK_KHXB_CXB|null $phieu */
        $phieu = $this->baseRepo->get($idPhieu);
        if (!$phieu || $phieu->IsDeleted) {
            throw new Exception('Phiếu trình CXB không tồn tại');
        }

        $soCvCxb = trim((string) ($data['SoCvCxb'] ?? ''));
        $soCvNxbgd = trim((string) ($data['SoCvNxbgd'] ?? ''));
        $ngayCap = $data['NgayCap'] ?? null;
        $namCap = trim((string) ($data['NamCap'] ?? ''));
        if ($namCap === '') {
            $namCap = (string) date('Y');
        }

        if ($soCvCxb === '') {
            throw new Exception('Vui lòng nhập số công văn xác nhận của CXB');
        }
        if ($soCvNxbgd === '') {
            throw new Exception('Vui lòng nhập số công văn của NXBGDVN');
        }
        if (empty($ngayCap)) {
            throw new Exception('Vui lòng nhập ngày cấp');
        }

        $maSoBase = trim((string) ($data['MaSoCxb'] ?? ''));
        if ($maSoBase === '') {
            throw new Exception('Vui lòng nhập mã số CXB');
        }

        $listIdDeTai = $this->loadListIdDeTaiFromChiTiet($idPhieu);
        if (count($listIdDeTai) === 0) {
            throw new Exception('Phiếu trình CXB chưa có đề tài để cấp mã');
        }

        $deTaiMap = $this->loadDeTaiMapByIds($listIdDeTai);

        /** @var CT_Detai_CongDoanService $congDoanService */
        $congDoanService = app(CT_Detai_CongDoanService::class);

        // Mỗi đề tài nhận mã riêng, số thứ tự (xxx) theo thứ tự trong phiếu
        foreach (array_values($listIdDeTai) as $index => $idDeTai) {
            /** @var PHIEU_DK_DETAI|null $deTai */
            $deTai = $deTaiMap[$idDeTai] ?? null;
            if (!$deTai) {
                continue;
            }
            $soThuTu = $index + 1;
            $maSoDeTai = $this->buildMaSoCxbHoanChinh($maSoBase, $namCap, $soThuTu, $soCvNxbgd);

            $deTai->MaSoCXB = $maSoDeTai;
            $deTai->SoGPXB = $maSoBase;
            $deTai->NgayCapPhep = $ngayCap;
            $deTai->EditedBy = $idCanBo;
            $deTai->EditedOn = now();
            $deTai->save();

            $congDoanService->ghiCongDoanTheoMaCD(
                $idDeTai,
                $idCanBo,
                CongDoanMa::CAP_MA_SO_CXB,
                null,
                'Mã CXB: ' . $maSoDeTai
            );
        }

        $phieu->PhanDauMaSo = $soCvCxb;
        $phieu->SoCvNXBGD = $soCvNxbgd;
        $phieu->SoGiayPhep = $maSoBase;
        $phieu->NgayCapPhep = $ngayCap;
        $phieu->EditedBy = $idCanBo;
        $phieu->EditedOn = now();
        $phieu->save();

        return [
            'phieu' => $phieu,
            'MaSoCXB' => $maSoBase,
            'listDeTai' => $this->loadDeTaiByIds($listIdDeTai),
        ];
    }

    private function buildMaSoCxbHoanChinh(string $maSoBase, string $namCap, int $soThuTu, string $soCvNxbgd): string
    {
        return sprintf('%s-%s/CXBIPH/%d-%s/GD', $maSoBase, $namCap, $soThuTu, $soCvNxbgd);
    }

    /**
     * Cấp mã ISBN cho từng đề tài thuộc phiếu trình CXB.
     * $isbnList: mảng [['id' => idDeTai, 'ISBNCode' => '...'], ...]
     */
    public function capMaIsbn(int $idPhieu, array $isbnList, int $idCanBo): array
    {
        /** @var PHIEU_DK_KHXB_CXB|null $phieu */
        $phieu = $this->baseRepo->get($idPhieu);
        if (!$phieu || $phieu->IsDeleted) {
            throw new Exception('Phiếu trình CXB không tồn tại');
        }

        $listIdDeTai = $this->loadListIdDeTaiFromChiTiet($idPhieu);
        $deTaiMap = $this->loadDeTaiMapByIds($listIdDeTai);

        // Validate toàn bộ trước, sai bất kỳ mã nào thì không lưu gì cả
        $updates = [];
        foreach ($isbnList as $item) {
            $idDeTai = (int) ($item['id'] ?? 0);
            if (!isset($deTaiMap[$idDeTai])) {
                continue;
            }
            $isbn = trim((string) ($item['ISBNCode'] ?? ''));
            if ($isbn !== '' && !$this->isValidIsbn($isbn)) {
                /** @var PHIEU_DK_DETAI $deTai */
                $deTai = $deTaiMap[$idDeTai];
                throw new Exception(sprintf('Mã ISBN [%s] của đề tài "%s" không hợp lệ', $isbn, (string) ($deTai->TenDeTai ?? $idDeTai)));
            }
            $updates[$idDeTai] = $isbn;
        }

        /** @var CT_Detai_CongDoanService $congDoanService */
        $congDoanService = app(CT_Detai_CongDoanService::class);

        foreach ($updates as $idDeTai => $isbn) {
            /** @var PHIEU_DK_DETAI $deTai */
            $deTai = $deTaiMap[$idDeTai];
            $deTai->ISBNCode = $isbn;
            $deTai->EditedBy = $idCanBo;
            $deTai->EditedOn = now();
            $deTai->save();

            if ($isbn !== '') {
                $congDoanService->ghiCongDoanTheoMaCD(
                    $idDeTai,
                    $idCanBo,
                    CongDoanMa::CAP_MA_ISBN,
                    null,
                    'ISBN: ' . $isbn
                );
            }
        }

        return [
            'phieu' => $phieu,
            'listDeTai' => $this->loadDeTaiByIds($listIdDeTai),
        ];
    }

    /**
     * Kết chuyển các đề tài đã chọn thành sách (ipub_dm_sach) và cập nhật trạng thái đề tài.
     */
    public function ketChuyenThanhSach(int $idPhieu, array $listIdDeTai, int $idCanBo): array
    {
        /** @var PHIEU_DK_KHXB_CXB|null $phieu */
        $phieu = $this->baseRepo->get($idPhieu);
        if (!$phieu || $phieu->IsDeleted) {
            throw new Exception('Phiếu trình CXB không tồn tại');
        }

        $listIdDeTai = $this->normalizeListIdDeTai($listIdDeTai);
        if (count($listIdDeTai) === 0) {
            throw new Exception('Vui lòng chọn ít nhất một đề tài để kết chuyển');
        }

        $idDeTaiTrongPhieu = array_flip($this->loadListIdDeTaiFromChiTiet($idPhieu));
        $deTaiMap = $this->loadDeTaiMapByIds($listIdDeTai);

        /** @var SachService $sachService */
        $sachService = app(SachService::class);
        /** @var SachRepository $sachRepo */
        $sachRepo = app(SachRepository::class);

        /** @var CT_Detai_CongDoanService $congDoanService */
        $congDoanService = app(CT_Detai_CongDoanService::class);

        $countKetChuyen = 0;
        foreach ($listIdDeTai as $idDeTai) {
            if (!isset($idDeTaiTrongPhieu[$idDeTai]) || !isset($deTaiMap[$idDeTai])) {
                continue;
            }

            /** @var PHIEU_DK_DETAI $deTai */
            $deTai = $deTaiMap[$idDeTai];

            $daKetChuyen = $sachRepo->findOne([
                'ID_DeTai' => $idDeTai,
                'IsDeleted' => false,
            ]);
            if ($daKetChuyen) {
                continue;
            }

            $sach = $sachService->store($this->mapDeTaiToSach($deTai, $idCanBo));

            $deTai->TrangThai = self::TRANG_THAI_KET_CHUYEN_THANH_SACH;
            $deTai->EditedBy = $idCanBo;
            $deTai->EditedOn = now();
            $deTai->save();

            $congDoanService->ghiCongDoanTheoMaCD(
                $idDeTai,
                $idCanBo,
                CongDoanMa::KET_CHUYEN_SACH,
                (int) ($sach->_id ?? 0) ?: null
            );

            $countKetChuyen++;
        }

        if ($countKetChuyen === 0) {
            throw new Exception('Các đề tài đã chọn đều đã được kết chuyển trước đó');
        }

        return [
            'phieu' => $phieu,
            'countKetChuyen' => $countKetChuyen,
            'listDeTai' => $this->loadDeTaiByIds($this->loadListIdDeTaiFromChiTiet($idPhieu)),
        ];
    }

    /**
     * Map dữ liệu đề tài sang bản ghi sách.
     *
     * @return array<string, mixed>
     */
    private function mapDeTaiToSach(PHIEU_DK_DETAI $deTai, int $idCanBo): array
    {
        return [
            'ID_DeTai' => (int) $deTai->_id,
            'MaSo' => (string) ($deTai->MaSo ?? ''),
            'MaSoCXB' => (string) ($deTai->MaSoCXB ?? ''),
            'NgayDK' => $deTai->NgayDk,
            'NgayCapPhep' => $deTai->NgayCapPhep,
            'TenSach' => (string) ($deTai->TenDeTai ?? ''),
            'BienTapVien' => (string) ($deTai->BienTapVien ?? ''),
            'LaSachDich' => (bool) ($deTai->LaDeTaiDich ?? false),
            'TenNguyenBan' => (string) ($deTai->TenNguyenBan ?? ''),
            'NguDuocDich' => (string) ($deTai->NguDuocDich ?? ''),
            'NguXuatBan' => (string) ($deTai->NguXuatBan ?? ''),
            'ThongTinSachDich' => (string) ($deTai->ThongTinSachDich ?? ''),
            'TacGia' => (string) ($deTai->TacGia ?? ''),
            'DichGia' => (string) ($deTai->DichGia ?? ''),
            'DeTaiTuongTu' => (string) ($deTai->DeTaiTuongTu ?? ''),
            'DeCuong' => (string) ($deTai->DeCuong ?? ''),
            'ID_MangSach_CXB' => (int) ($deTai->ID_MangSach_CXB ?? 0),
            'ID_LoaiXBP' => (int) ($deTai->ID_LoaiXBP ?? 0),
            'ID_TuSach' => (int) ($deTai->ID_TuSach ?? 0),
            'ID_DonVi' => (int) ($deTai->ID_DonVi ?? 0),
            'ID_DVLK' => (int) ($deTai->ID_DonViLK ?? 0),
            'ID_MonHoc' => (int) ($deTai->ID_MonHoc ?? 0),
            'ID_MangSach' => (int) ($deTai->ID_MangSach ?? 0),
            'ID_Lop' => (int) ($deTai->ID_Lop ?? 0),
            'ID_Cap' => (int) ($deTai->ID_Cap ?? 0),
            'HTXB' => (bool) ($deTai->HTXB ?? false),
            'PTXB' => (bool) ($deTai->PTXB ?? false),
            'ThoiDiemCoDuBT' => (string) ($deTai->ThoiDiemCoDuBT ?? ''),
            'ThoiDiemRaSach' => (string) ($deTai->ThoiDiemRaSach ?? ''),
            'SoTrang' => (int) ($deTai->SoTrangDK ?? 0),
            'Dai' => (string) ($deTai->Dai ?? ''),
            'Rong' => (string) ($deTai->Rong ?? ''),
            'GiaBia' => (int) ($deTai->GiaBia ?? 0),
            'NamXuatBan' => (string) ($deTai->NamXuatBan ?? ''),
            'NamTaiBan' => (string) ($deTai->NamTaiBan ?? ''),
            'LanTaiBan' => (int) ($deTai->LanTaiBan ?? 0),
            'SoLuong' => (int) ($deTai->SoLuongDK ?? 0),
            'MauInRuot' => (int) ($deTai->MauInRuot ?? 0),
            'MauBia' => (int) ($deTai->MauInBia ?? 0),
            'NoiDung' => (string) ($deTai->NoiDung ?? ''),
            'GhiChu' => (string) ($deTai->GhiChu ?? ''),
            'ISBNCode' => (string) ($deTai->ISBNCode ?? ''),
            'MaSoQTG' => (string) ($deTai->MaSoQTG ?? ''),
            'VongThau' => (int) ($deTai->VongThau ?? 0),
            'LaDeTaiCKH' => (bool) ($deTai->LaDeTaiCKH ?? false),
            'ThongTinLienQuan' => (string) ($deTai->ThongTinLienQuan ?? ''),
            'FMAVACH' => (string) ($deTai->FMAVACH ?? ''),
            'BanQuyen' => (bool) ($deTai->BanQuyen ?? false),
            'CoMSISBN' => (bool) ($deTai->CoMSISBN ?? false),
            'IsSachDienTu' => (bool) ($deTai->IsSachDienTu ?? false),
            'DinhDangTep' => (string) ($deTai->DinhDangTep ?? ''),
            'DungLuongTep' => (string) ($deTai->DungLuongTep ?? ''),
            'DiaChiCungCap' => (string) ($deTai->DiaChiCungCap ?? ''),
            'LuaTuoi' => (string) ($deTai->LuaTuoi ?? ''),
            'TypeLuaTuoi' => (int) ($deTai->TypeLuaTuoi ?? 0),
            'HoanThanh' => false,
            'IsDeleted' => false,
            'DaGui' => false,
            'CreatedBy' => $idCanBo,
            'CreatedOn' => now(),
            'EditedBy' => $idCanBo,
            'EditedOn' => now(),
        ];
    }

    /**
     * Kiểm tra mã ISBN hợp lệ theo chuẩn ISBN-13 hoặc ISBN-10 (bỏ qua dấu gạch nối / khoảng trắng).
     */
    private function isValidIsbn(string $isbn): bool
    {
        $code = strtoupper(preg_replace('/[\s-]+/', '', $isbn));

        if (preg_match('/^\d{13}$/', $code)) {
            return $this->isValidIsbn13($code);
        }

        if (preg_match('/^\d{9}[\dX]$/', $code)) {
            return $this->isValidIsbn10($code);
        }

        return false;
    }

    private function isValidIsbn13(string $code): bool
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $code[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        $check = (10 - ($sum % 10)) % 10;

        return $check === (int) $code[12];
    }

    private function isValidIsbn10(string $code): bool
    {
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $code[$i]) * (10 - $i);
        }
        $last = $code[9];
        $sum += ($last === 'X') ? 10 : (int) $last;

        return $sum % 11 === 0;
    }

    private function resolveNextCxbSeq(bool $allocate): int
    {
        /** @var CountersOlm|null $counter */
        $counter = CountersOlm::where('_id', self::MA_SO_CXB_COUNTER_KEY)->first();
        $nextSeq = (int) ($counter->seq ?? 0) + 1;

        if ($allocate) {
            CountersOlm::updateOrCreate(
                ['_id' => self::MA_SO_CXB_COUNTER_KEY],
                ['seq' => $nextSeq]
            );
        }

        return $nextSeq;
    }

    /**
     * Thêm phiếu trình CXB
     */
    public function store(array $data, int $idCanBo): array
    {
        $id = (int) ($data['id'] ?? 0);
        $listIdDeTai = $this->normalizeListIdDeTai($data['listIdDeTai'] ?? []);

        if ($id > 0) {
            /** @var PHIEU_DK_KHXB_CXB|null $phieu */
            $phieu = $this->baseRepo->get($id);
            if (!$phieu || $phieu->IsDeleted) {
                throw new Exception('Phiếu trình CXB không tồn tại');
            }

            $phieu->fill([
                'TieuDe' => (string) ($data['TieuDe'] ?? ''),
                'NoiDung' => (string) ($data['NoiDung'] ?? ''),
                'NoiNhan2' => (string) ($data['NoiNhan2'] ?? ''),
                'NgayDK' => $data['NgayDK'] ?? $phieu->NgayDK,
                'ID_NguoiKi' => isset($data['ID_NguoiKi']) ? (int) $data['ID_NguoiKi'] : null,
                'KiThay' => (bool) ($data['KiThay'] ?? false),
                'EditedBy' => $idCanBo,
                'EditedOn' => now(),
            ]);
            if (trim((string) ($phieu->MaSo ?? '')) === '') {
                $phieu->MaSo = $this->generateMaSo();
            }
            $phieu->save();
            $this->syncChiTietDeTai((int) $phieu->_id, $listIdDeTai, $idCanBo, $phieu);
        } else {
            $maSo = trim((string) ($data['MaSo'] ?? ''));
            if ($maSo === '') {
                $maSo = $this->generateMaSo();
            } else {
                $this->assertMaSoAvailable($maSo, 0);
            }

            /** @var PHIEU_DK_KHXB_CXB $phieu */
            $phieu = $this->counterRepo->retry('ipub_phieu_dk_khxb_cxb', function ($newId) use ($data, $maSo, $idCanBo) {
                return $this->baseRepo->create([
                    '_id' => $newId,
                    'MaSo' => $maSo,
                    'TieuDe' => (string) ($data['TieuDe'] ?? ''),
                    'NoiDung' => (string) ($data['NoiDung'] ?? ''),
                    'NoiNhan2' => (string) ($data['NoiNhan2'] ?? ''),
                    'NgayDK' => $data['NgayDK'] ?? now(),
                    'ID_NguoiKi' => isset($data['ID_NguoiKi']) ? (int) $data['ID_NguoiKi'] : null,
                    'KiThay' => (bool) ($data['KiThay'] ?? false),
                    'DaGui' => false,
                    'InUsed' => true,
                    'IsDeleted' => false,
                    'CreatedBy' => $idCanBo,
                    'CreatedOn' => now(),
                    'EditedBy' => $idCanBo,
                    'EditedOn' => now(),
                ]);
            });
            $this->syncChiTietDeTai((int) $phieu->_id, $listIdDeTai, $idCanBo, $phieu);
            $this->ghiCongDoanTaoDonDkXuatBan($listIdDeTai, $idCanBo);
        }

        return [
            'phieu' => $phieu,
            'listDeTai' => $this->loadDeTaiByIds($listIdDeTai),
        ];
    }

    private function loadListIdDeTaiFromChiTiet(int $idPhieu): array
    {
        $listCt = $this->ctPhieuDkKhxbCxbRepo->findAll(
            [
                'ID_PhieuDK' => $idPhieu,
                'IsDeleted' => false,
            ],
            ['ThuTuTrongPhieu' => 'asc']
        );

        $listIdDeTai = [];
        foreach ($listCt as $ct) {
            $listIdDeTai[] = (int) $ct->ID_DeTai;
        }

        return $listIdDeTai;
    }

    private function syncChiTietDeTai(int $idPhieu, array $listIdDeTai, int $idCanBo, PHIEU_DK_KHXB_CXB $phieu): void
    {
        $existing = $this->ctPhieuDkKhxbCxbRepo->findAll([
            'ID_PhieuDK' => $idPhieu,
            'IsDeleted' => false,
        ]);

        $existingMap = [];
        foreach ($existing as $ct) {
            $existingMap[(int) $ct->ID_DeTai] = $ct;
        }

        $targetIds = array_flip($listIdDeTai);
        $khoaGuiNhan = (string) ($phieu->KhoaGuiNhan ?? '');
        $daGui = (bool) ($phieu->DaGui ?? false);

        foreach ($existingMap as $idDeTai => $ct) {
            if (!isset($targetIds[$idDeTai])) {
                $ct->IsDeleted = true;
                $ct->EditedBy = $idCanBo;
                $ct->EditedOn = now();
                $ct->save();
            }
        }

        $deTaiMap = $this->loadDeTaiMapByIds($listIdDeTai);

        foreach ($listIdDeTai as $index => $idDeTai) {
            $thuTu = ($index + 1) * 10;
            /** @var PHIEU_DK_DETAI|null $deTai */
            $deTai = $deTaiMap[$idDeTai] ?? null;
            $namXuatBan = $deTai ? (string) ($deTai->NamXuatBan ?: $deTai->NamTaiBan ?? '') : '';
            $trangThai = $deTai ? (int) ($deTai->TrangThai ?? 0) : 0;
            $liDo = $deTai ? (string) ($deTai->LiDo ?? '') : '';

            if (isset($existingMap[$idDeTai])) {
                $ct = $existingMap[$idDeTai];
                $ct->ThuTuTrongPhieu = $thuTu;
                $ct->NamXuatBan = $namXuatBan;
                $ct->TrangThai = $trangThai;
                $ct->LiDo = $liDo;
                $ct->DaGui = $daGui;
                $ct->KhoaGuiNhan = $khoaGuiNhan;
                $ct->EditedBy = $idCanBo;
                $ct->EditedOn = now();
                $ct->save();
                continue;
            }

            $this->counterRepo->retry('ipub_ct_phieu_dk_khxb_cxb', function ($newId) use (
                $idPhieu,
                $idDeTai,
                $thuTu,
                $namXuatBan,
                $trangThai,
                $liDo,
                $daGui,
                $khoaGuiNhan,
                $idCanBo
            ) {
                return $this->ctPhieuDkKhxbCxbRepo->create([
                    '_id' => $newId,
                    'ID_PhieuDK' => $idPhieu,
                    'ID_DeTai' => $idDeTai,
                    'ThuTuTrongPhieu' => $thuTu,
                    'NamXuatBan' => $namXuatBan,
                    'TrangThai' => $trangThai,
                    'LiDo' => $liDo,
                    'DaGui' => $daGui,
                    'InUsed' => true,
                    'IsDeleted' => false,
                    'KhoaGuiNhan' => $khoaGuiNhan,
                    'CreatedBy' => $idCanBo,
                    'CreatedOn' => now(),
                    'EditedBy' => $idCanBo,
                    'EditedOn' => now(),
                ]);
            });
        }
    }

    /** @return array<int, PHIEU_DK_DETAI> */
    private function loadDeTaiMapByIds(array $listIdDeTai): array
    {
        if (count($listIdDeTai) === 0) {
            return [];
        }

        $map = [];
        $list = $this->phieuDkDetaiRepo->findAll([
            '_id' => ['$in' => array_values($listIdDeTai)],
            'IsDeleted' => false,
        ]);

        foreach ($list as $deTai) {
            $map[(int) $deTai->_id] = $deTai;
        }

        return $map;
    }

    /** @param mixed $listIdDeTai */
    private function normalizeListIdDeTai($listIdDeTai): array
    {
        if (!is_array($listIdDeTai)) {
            return [];
        }

        return array_values(array_unique(array_map('intval', $listIdDeTai)));
    }

    private function loadDeTaiByIds(array $listIdDeTai): array
    {
        if (count($listIdDeTai) === 0) {
            return [];
        }

        return $this->phieuDkDetaiRepo->findAll([
            '_id' => ['$in' => array_values($listIdDeTai)],
            'IsDeleted' => false,
        ])->values()->all();
    }

    private function generateMaSo(): string
    {
        do {
            $seq = $this->resolveNextMaSoSeq(true);
            $candidate = $this->buildMaSoFromNextSeq($seq);
            $exists = $this->baseRepo->findOne([
                'MaSo' => $candidate,
                'IsDeleted' => false,
            ]);
            if (!$exists) {
                return $candidate;
            }
        } while (true);
    }

    private function resolveNextMaSoSeq(bool $allocate): int
    {
        $maxDb = $this->findMaxMaSoSeqInDatabase();
        /** @var CountersOlm|null $counter */
        $counter = CountersOlm::where('_id', self::MA_SO_COUNTER_KEY)->first();
        $currentSeq = (int) ($counter->seq ?? 0);
        $nextSeq = max($currentSeq, $maxDb) + 1;

        if ($allocate) {
            CountersOlm::updateOrCreate(
                ['_id' => self::MA_SO_COUNTER_KEY],
                ['seq' => $nextSeq]
            );
        }

        return $nextSeq;
    }

    private function findMaxMaSoSeqInDatabase(): int
    {
        $list = $this->baseRepo->findAll(
            [
                'MaSo' => ['$regex' => new Regex('^CXB\\d+$', 'i')],
                'IsDeleted' => false,
            ],
            [],
            ['MaSo']
        );

        $max = 0;
        foreach ($list as $phieu) {
            if (preg_match('/^CXB(\d+)$/i', (string) ($phieu->MaSo ?? ''), $matches)) {
                $max = max($max, (int) $matches[1]);
            }
        }

        return $max;
    }

    private function buildMaSoFromNextSeq(int $seq): string
    {
        return 'CXB' . $seq;
    }

    private function assertMaSoAvailable(string $maSo, int $excludeId): void
    {
        $duplicate = $this->baseRepo->findOne([
            'MaSo' => $maSo,
            'IsDeleted' => false,
            '_id' => ['$ne' => $excludeId],
        ]);
        if ($duplicate) {
            throw new Exception("Mã phiếu [{$maSo}] đã được sử dụng");
        }
    }

    public function getXetDuyet(int $idPhieu): array
    {
        /** @var PHIEU_DK_KHXB_CXB|null $phieu */
        $phieu = $this->baseRepo->get($idPhieu);
        if (!$phieu || $phieu->IsDeleted) {
            throw new Exception('Phiếu trình CXB không tồn tại');
        }

        $listCt = $this->ctPhieuDkKhxbCxbRepo->findAll(
            [
                'ID_PhieuDK' => $idPhieu,
                'IsDeleted' => false,
            ],
            ['ThuTuTrongPhieu' => 'asc']
        );

        $listIdDeTai = [];
        foreach ($listCt as $ct) {
            $listIdDeTai[] = (int) $ct->ID_DeTai;
        }

        $deTaiMap = $this->loadDeTaiMapByIds($listIdDeTai);
        $donviMap = $this->loadDonviMapByDeTai($deTaiMap);

        $listDeTai = [];
        foreach ($listCt as $ct) {
            $idDeTai = (int) $ct->ID_DeTai;
            /** @var PHIEU_DK_DETAI|null $deTai */
            $deTai = $deTaiMap[$idDeTai] ?? null;
            if (!$deTai) {
                continue;
            }

            $idDonVi = (int) ($deTai->ID_DonVi ?? 0);
            $listDeTai[] = [
                'id' => $idDeTai,
                'idCt' => (int) $ct->_id,
                'TenDeTai' => (string) ($deTai->TenDeTai ?? ''),
                'TenDonVi' => $donviMap[$idDonVi] ?? '',
                'TrangThai' => (int) ($deTai->TrangThai ?? $ct->TrangThai ?? 0),
            ];
        }

        return [
            'phieu' => $phieu,
            'listDeTai' => $listDeTai,
        ];
    }

    public function luuXetDuyet(int $idPhieu, array $items, int $idCanBo): array
    {
        if ($idCanBo <= 0) {
            throw new Exception('Người dùng chưa đăng nhập');
        }

        /** @var PHIEU_DK_KHXB_CXB|null $phieu */
        $phieu = $this->baseRepo->get($idPhieu);
        if (!$phieu || $phieu->IsDeleted) {
            throw new Exception('Phiếu trình CXB không tồn tại');
        }

        $allowedTrangThai = array_flip(PhieuDkDetaiTrangThai::cxbXetDuyetValues());
        $idDeTaiTrongPhieu = array_flip($this->loadListIdDeTaiFromChiTiet($idPhieu));

        /** @var CT_Detai_CongDoanService $congDoanService */
        $congDoanService = app(CT_Detai_CongDoanService::class);

        $count = 0;
        $now = now();

        foreach ($items as $item) {
            $idDeTai = (int) ($item['idDeTai'] ?? 0);
            $trangThaiMoi = (int) ($item['TrangThai'] ?? -1);

            if ($idDeTai <= 0 || !isset($idDeTaiTrongPhieu[$idDeTai]) || !isset($allowedTrangThai[$trangThaiMoi])) {
                continue;
            }

            /** @var PHIEU_DK_DETAI|null $deTai */
            $deTai = $this->phieuDkDetaiRepo->get($idDeTai);
            if (!$deTai) {
                continue;
            }

            $trangThaiCu = (int) ($deTai->TrangThai ?? 0);
            if ($trangThaiCu === $trangThaiMoi) {
                continue;
            }

            $deTai->TrangThai = $trangThaiMoi;
            $deTai->EditedBy = $idCanBo;
            $deTai->EditedOn = $now;
            $deTai->save();

            $ct = $this->ctPhieuDkKhxbCxbRepo->findOne([
                'ID_PhieuDK' => $idPhieu,
                'ID_DeTai' => $idDeTai,
                'IsDeleted' => false,
            ]);
            if ($ct) {
                $ct->TrangThai = $trangThaiMoi;
                $ct->EditedBy = $idCanBo;
                $ct->EditedOn = $now;
                $ct->save();
            }

            $congDoanService->ghiCongDoanTrangThai($idDeTai, $idCanBo, $trangThaiCu, $trangThaiMoi);
            $count++;
        }

        if ($count === 0) {
            throw new Exception('Không có đề tài nào được cập nhật');
        }

        return [
            'phieu' => $phieu,
            'count' => $count,
            'listDeTai' => $this->getXetDuyet($idPhieu)['listDeTai'],
        ];
    }

    /** @param array<int, PHIEU_DK_DETAI> $deTaiMap */
    private function loadDonviMapByDeTai(array $deTaiMap): array
    {
        $idsDonVi = [];
        foreach ($deTaiMap as $deTai) {
            $idDonVi = (int) ($deTai->ID_DonVi ?? 0);
            if ($idDonVi > 0) {
                $idsDonVi[$idDonVi] = true;
            }
        }

        if (count($idsDonVi) === 0) {
            return [];
        }

        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);
        $map = [];
        foreach (array_keys($idsDonVi) as $idDonVi) {
            $donvi = $donviService->findOne('no-cache', ['_id' => $idDonVi]);
            if ($donvi) {
                $map[$idDonVi] = (string) ($donvi->TenDonVi ?? '');
            }
        }

        return $map;
    }

    /**
     * @param int[] $listIdDeTai
     */
    private function ghiCongDoanTaoDonDkXuatBan(array $listIdDeTai, int $idCanBo): void
    {
        if ($idCanBo <= 0 || count($listIdDeTai) === 0) {
            return;
        }

        /** @var CT_Detai_CongDoanService $congDoanService */
        $congDoanService = app(CT_Detai_CongDoanService::class);

        foreach ($listIdDeTai as $idDeTai) {
            $idDeTai = (int) $idDeTai;
            if ($idDeTai <= 0) {
                continue;
            }
            $congDoanService->ghiCongDoanTheoMaCD(
                $idDeTai,
                $idCanBo,
                CongDoanMa::TAO_DON_DK_XUAT_BAN
            );
        }
    }
}
