<?php
namespace Modules\Topic\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\Topic\Service\PhieuDkDetaiService;
use Modules\Topic\Repository\PhieuDkDetaiRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Core\Object\Paginate;
use Core\Service\BaseConvertTool;
use Modules\Topic\Object\CongDoanMa;
use Modules\Topic\Object\FilterPhieuDkDetai;
use Modules\Topic\Model\PHIEU_DK_DETAI;

use Exception;
use ExportFile\ExportFile;
use Modules\System\Object\FilterTrangThai;
use Modules\System\Service\DonviService;
use Modules\System\Service\TrangThaiService;
use Modules\Topic\Object\PhieuDkDetaiTrangThai;
use Modules\Topic\Service\CT_Detai_CongDoanService;
use Modules\Topic\Service\CT_PhieuDkDetai_BtvService;

class PhieuDkDetaiServiceImpl extends BaseService implements PhieuDkDetaiService
{
    use BaseConvertTool;
    /** @var PhieuDkDetaiRepository */
    protected $baseRepo;

    public function __construct(PhieuDkDetaiRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterPhieuDkDetai $filter, string $page = 'page-1'): array {
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

    public function getList(FilterPhieuDkDetai $filter) {
        return $this->baseRepo->findAllWithFilter($filter);
    }

    public function store(array $data): PHIEU_DK_DETAI {
        $id = (int) data_get($data, "id", 0);
        unset($data["id"], $data["_id"]);

        $isCreate = $id === 0;

        if(!$isCreate) {
            /** @var PHIEU_DK_DETAI $phieuDkDetai */
            $phieuDkDetai = $this->baseRepo->get($id);
            if($phieuDkDetai) {
                $phieuDkDetai->update($data);
                return $phieuDkDetai;
            }
        }

        /** @var PHIEU_DK_DETAI $phieuDkDetai */
        $phieuDkDetai = $this->baseRepo->create($data);
        if(!$phieuDkDetai){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }

        if ($isCreate) {
            $idCanBo = (int) ($data['CreatedBy'] ?? Auth::id() ?? 0);
            if ($idCanBo > 0) {
                /** @var CT_Detai_CongDoanService $congDoanService */
                $congDoanService = app(CT_Detai_CongDoanService::class);
                $congDoanService->ghiCongDoanTheoMaCD(
                    (int) $phieuDkDetai->_id,
                    $idCanBo,
                    CongDoanMa::TAO_PHIEU_DK
                );
            }
        }

        return $phieuDkDetai;
    }

    public function delete(int $id): bool {
        $phieuDkDetai = $this->baseRepo->get($id);
        if(!$phieuDkDetai){
            throw new Exception("PhieuDkDetai không tồn tại");
        }
        $phieuDkDetai->IsDeleted = true;
        $saved = $phieuDkDetai->save();

        if ($saved) {
            $idCanBo = (int) (Auth::id() ?? 0);
            if ($idCanBo > 0) {
                /** @var CT_Detai_CongDoanService $congDoanService */
                $congDoanService = app(CT_Detai_CongDoanService::class);
                $congDoanService->ghiCongDoanTheoMaCD(
                    (int) $phieuDkDetai->_id,
                    $idCanBo,
                    CongDoanMa::HUY_DE_TAI
                );
            }
        }

        return $saved;
    }

    public function printPhieuDkDeTai(int $id, array $data): string {
        $phieuDkDetai = $this->baseRepo->get($id);
        if(!$phieuDkDetai){
            throw new Exception("Phiếu đăng ký đề tài không tồn tại");
        }

        $templateName = (string) data_get($data, "template_name", "Template_Phieu_DK_DE_TAI");
        $templateFormat = (string) data_get($data, "template_format", "pdf");
        $pathFileDocx = (string) data_get($data, "path_file_docx", "");
        $exportFile = new ExportFile($templateName, [
            "phieu_dk_detai" => $phieuDkDetai,
        ], $templateFormat);
        if ($pathFileDocx !== '' && is_file($pathFileDocx)) {
            $exportFile->setPathFileDocx($pathFileDocx);
        }

        return $exportFile->export();
    }
    /**
     * hàm này nhận dữ liệu callback từ ExportFile để thay đổi giá trị khi cần thiết
     *
    */
    public function helperPrintPhieuDkDeTai(array $data): array {
        if (data_get($data, "!CanhBao!", false)) {
            $data['!CanhBao!'] = "";
            $data['!1CanhBao!'] = "X";
        } else {
            $data['!CanhBao!'] = "X";
            $data['!1CanhBao!'] = "";
        }

        // true nếu "!Lop!" chứa chuỗi "Lớp"
        if((string) data_get($data, "!Lop!", "") !== ""){
            $befString = "";
            if (str_contains((string) data_get($data, "!Lop!", ""), "Lớp")) {
                $befString = "9.2 ";
            }else{
                $befString = "9.3 ";
            }
            $data["!Lop!"] = $befString . (string) data_get($data, "!Lop!", "");
        }
        dd($data);
        return $data;
    }

    public function convertDataListBTV(){
        dump("convertDataListBTV: START");
        $this->baseRepo->update(
            ["BienTapVien" => ""],
            ["idListBTV" => []]
          );
        dump("convertDataListBTV: FINISH  BienTapVien = __");
        $this->baseConvert("convertDataListBTV", $this->baseRepo, [
            "BienTapVien" => ['$ne' => ""]
        ], function($phieuDkDetai) {
            $listBTV = [];
            if(isset($phieuDkDetai->BienTapVien) && $phieuDkDetai->BienTapVien != ""){
                /** @var CT_PhieuDkDetai_BtvService $ct_phieuDkDetai_bTVService */
                $ct_phieuDkDetai_bTVService = app(CT_PhieuDkDetai_BtvService::class);
                $listCTPhieuDkDetai = $ct_phieuDkDetai_bTVService->findAll([
                    "ID_PHIEU_DK_DETAI" => $phieuDkDetai->_id,
                    "IsDeleted" => false
                ]);
                foreach ($listCTPhieuDkDetai as $ctPhieuDkDetai) {
                    $listBTV[] = $ctPhieuDkDetai->ID_CANBO;
                }
            }
            $phieuDkDetai->idListBTV = $listBTV;
            $phieuDkDetai->save();
        });
        dump("convertDataListBTV: FINISH");
    }
    /** xét duyệt đề tài (HĐXB đơn vị) */
    public function xetDuyetDeTai(int $id, int $idCanBo): PHIEU_DK_DETAI {
        /** @var PHIEU_DK_DETAI|null $phieuDkDetai */
        $phieuDkDetai = $this->baseRepo->get($id);
        if (!$phieuDkDetai) {
            throw new Exception("Phiếu đăng ký đề tài không tồn tại");
        }

        $trangThaiHienTai = (int) ($phieuDkDetai->TrangThai ?? 0);
        $trangThaiMoi = $this->resolveTrangThaiXetDuyetDonVi($trangThaiHienTai);
        $this->assertTrangThaiChoPhep($trangThaiHienTai, $this->getTrangThaiChoPhepXetDuyetDonVi($trangThaiHienTai));
        $this->assertTrangThaiTonTai($trangThaiMoi);

        $phieuDkDetai->IsXetDuyet = true;
        $phieuDkDetai->TrangThai = $trangThaiMoi;
        $phieuDkDetai->EditedBy = $idCanBo;
        $phieuDkDetai->EditedOn = now();
        $phieuDkDetai->save();

        $this->ghiCongDoanXetDuyet((int) $phieuDkDetai->_id, $idCanBo, $trangThaiHienTai, $trangThaiMoi);

        return $phieuDkDetai;
    }

    /** xét duyệt NXBGDVN */
    public function xetDuyetNxbgdvn(int $id, int $idCanBo): PHIEU_DK_DETAI {
        /** @var PHIEU_DK_DETAI|null $phieuDkDetai */
        $phieuDkDetai = $this->baseRepo->get($id);
        if (!$phieuDkDetai) {
            throw new Exception("Phiếu đăng ký đề tài không tồn tại");
        }

        $trangThaiHienTai = (int) ($phieuDkDetai->TrangThai ?? 0);
        $allowedFrom = [
            PhieuDkDetaiTrangThai::CHUA_XET_DUYET,
            PhieuDkDetaiTrangThai::HDXB_DON_VI_TRA_LAI,
            PhieuDkDetaiTrangThai::HDXB_DON_VI_DANG_XET,
            PhieuDkDetaiTrangThai::HDXB_DON_VI_PHE_DUYET,
            PhieuDkDetaiTrangThai::HDXB_NXBGDVN_TRA_LAI,
            PhieuDkDetaiTrangThai::HDXB_NXBGDVN_CHUA_XET,
        ];
        $trangThaiMoi = PhieuDkDetaiTrangThai::HDXB_NXBGDVN_DANG_XET;

        $this->assertTrangThaiChoPhep($trangThaiHienTai, $allowedFrom);
        $this->assertTrangThaiTonTai($trangThaiMoi);

        $phieuDkDetai->IsXetDuyet = true;
        $phieuDkDetai->TrangThai = $trangThaiMoi;
        $phieuDkDetai->EditedBy = $idCanBo;
        $phieuDkDetai->EditedOn = now();
        $phieuDkDetai->save();

        $this->ghiCongDoanXetDuyet((int) $phieuDkDetai->_id, $idCanBo, $trangThaiHienTai, $trangThaiMoi);

        return $phieuDkDetai;
    }

    public function previewMaSoNxbgd(int $idDeTai, bool $isMa12KiTu): string
    {
        /** @var PHIEU_DK_DETAI|null $phieu */
        $phieu = $this->baseRepo->get($idDeTai);
        if (!$phieu) {
            throw new Exception('Phiếu đăng ký đề tài không tồn tại');
        }

        return $this->generateMaSoNxbgd($phieu, $isMa12KiTu);
    }

    public function capMaSoNxbgd(int $idDeTai, string $maSo, bool $isMa12KiTu, int $idCanBo): PHIEU_DK_DETAI
    {
        /** @var PHIEU_DK_DETAI|null $phieu */
        $phieu = $this->baseRepo->get($idDeTai);
        if (!$phieu) {
            throw new Exception('Phiếu đăng ký đề tài không tồn tại');
        }

        $maSo = strtoupper(trim($maSo));
        if ($maSo === '') {
            throw new Exception('Vui lòng nhập mã số muốn cấp');
        }

        $expectedLength = $isMa12KiTu ? 12 : 7;
        if (strlen($maSo) !== $expectedLength) {
            throw new Exception("Mã số phải có đúng {$expectedLength} ký tự");
        }

        $duplicate = $this->baseRepo->findOne([
            'MaSo' => $maSo,
            'IsDeleted' => false,
            '_id' => ['$ne' => (int) $phieu->_id],
        ]);
        if ($duplicate) {
            throw new Exception("Mã số [{$maSo}] đã được sử dụng");
        }

        $phieu->MaSo = $maSo;
        $phieu->isMa12KiTu = $isMa12KiTu;
        $phieu->EditedBy = $idCanBo;
        $phieu->EditedOn = now();
        $phieu->save();

        return $phieu;
    }

    private function generateMaSoNxbgd(PHIEU_DK_DETAI $phieu, bool $isMa12KiTu): string
    {
        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);
        $donvi = $donviService->findOne('no-cache', ['id' => (int) ($phieu->ID_DonVi ?? 0)]);
        $maDonViRaw = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string) ($donvi->MaDonVi ?? 'XXXX')));
        $maDonVi4 = substr(str_pad($maDonViRaw, 4, 'X'), 0, 4);

        $year = (int) date('y');
        $namXb = (string) ($phieu->NamXuatBan ?? $phieu->NamTaiBan ?? '');
        if ($namXb !== '' && preg_match('/(\d{2,4})$/', $namXb, $matches)) {
            $yearPart = $matches[1];
            $year = (int) substr($yearPart, -2);
        }

        $counterKey = 'ma_so_nxbgd_' . (int) ($phieu->ID_DonVi ?? 0) . '_' . $year . ($isMa12KiTu ? '_12' : '_7');
        $seq = (int) $this->counterRepo->increment($counterKey, false);

        do {
            $candidate = $isMa12KiTu
                ? sprintf('G0%s%03dY%02d', $maDonVi4, $seq % 1000, $year)
                : sprintf('G0%s%03d', substr($maDonVi4, 0, 2), $seq % 1000);

            $exists = $this->baseRepo->findOne([
                'MaSo' => $candidate,
                'IsDeleted' => false,
            ]);
            if (!$exists) {
                return $candidate;
            }
            $seq = (int) $this->counterRepo->increment($counterKey, false);
        } while (true);
    }

    /** ghi công đoạn */
    private function ghiCongDoanXetDuyet(int $idDeTai, int $idCanBo, int $trangThaiCu, int $trangThaiMoi): void {
        if ($trangThaiCu === $trangThaiMoi) {
            return;
        }
        /** @var CT_Detai_CongDoanService $detaiCongDoanService */
        $detaiCongDoanService = app(CT_Detai_CongDoanService::class);
        $detaiCongDoanService->ghiCongDoanTrangThai($idDeTai, $idCanBo, $trangThaiCu, $trangThaiMoi);
    }

    private function getMapTrangThai(): array {
        /** @var TrangThaiService $trangThaiService */
        $trangThaiService = app(TrangThaiService::class);
        return $trangThaiService->getMapTrangThai(new FilterTrangThai(['DaGui' => true]));
    }

    private function getTenTrangThai(int $maTrangThai): string {
        $map = $this->getMapTrangThai();
        return $map[$maTrangThai] ?? (string) $maTrangThai;
    }

    private function assertTrangThaiTonTai(int $maTrangThai): void {
        $map = $this->getMapTrangThai();
        if (!isset($map[$maTrangThai])) {
            throw new Exception("Trạng thái [{$maTrangThai}] không tồn tại trong hệ thống");
        }
    }

    private function assertTrangThaiChoPhep(int $trangThaiHienTai, array $allowedFrom): void {
        if (!in_array($trangThaiHienTai, $allowedFrom, true)) {
            $tenTrangThai = $this->getTenTrangThai($trangThaiHienTai);
            throw new Exception("Đề tài đang ở trạng thái [{$tenTrangThai}], không thể thực hiện thao tác này");
        }
    }

    /** @return int[] */
    private function getTrangThaiChoPhepXetDuyetDonVi(int $trangThaiHienTai): array {
        if (in_array($trangThaiHienTai, [
            PhieuDkDetaiTrangThai::CHUA_XET_DUYET,
            PhieuDkDetaiTrangThai::HDXB_DON_VI_TRA_LAI,
        ], true)) {
            return [
                PhieuDkDetaiTrangThai::CHUA_XET_DUYET,
                PhieuDkDetaiTrangThai::HDXB_DON_VI_TRA_LAI,
            ];
        }

        if ($trangThaiHienTai === PhieuDkDetaiTrangThai::HDXB_DON_VI_DANG_XET) {
            return [PhieuDkDetaiTrangThai::HDXB_DON_VI_DANG_XET];
        }

        return [];
    }

    private function resolveTrangThaiXetDuyetDonVi(int $trangThaiHienTai): int {
        if (in_array($trangThaiHienTai, [
            PhieuDkDetaiTrangThai::CHUA_XET_DUYET,
            PhieuDkDetaiTrangThai::HDXB_DON_VI_TRA_LAI,
        ], true)) {
            return PhieuDkDetaiTrangThai::HDXB_DON_VI_DANG_XET;
        }

        if ($trangThaiHienTai === PhieuDkDetaiTrangThai::HDXB_DON_VI_DANG_XET) {
            return PhieuDkDetaiTrangThai::HDXB_DON_VI_PHE_DUYET;
        }

        return $trangThaiHienTai;
    }
}
