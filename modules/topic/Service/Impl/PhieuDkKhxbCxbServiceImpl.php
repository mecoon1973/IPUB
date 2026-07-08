<?php

namespace Modules\Topic\Service\Impl;

use Core\Model\CountersOlm;
use Core\Object\Paginate;
use Core\Service\BaseService;
use Exception;
use MongoDB\BSON\Regex;
use Modules\Topic\Model\PHIEU_DK_KHXB_CXB;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\FilterPhieuDkKhxbCxb;
use Modules\Topic\Repository\CT_PhieuDkKhxbCxbRepository;
use Modules\Topic\Repository\PhieuDkDetaiRepository;
use Modules\Topic\Repository\PhieuDkKhxbCxbRepository;
use Modules\Topic\Service\PhieuDkKhxbCxbService;

class PhieuDkKhxbCxbServiceImpl extends BaseService implements PhieuDkKhxbCxbService
{
    private const MA_SO_COUNTER_KEY = 'ma_so_phieu_dk_khxb_cxb';

    private const MA_SO_CXB_COUNTER_KEY = 'ma_so_cxb_iph';

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
}
