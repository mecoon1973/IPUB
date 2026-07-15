<?php
namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\FilterPhieuDkDetai;

/**
 * @extends IBaseService<PHIEU_DK_DETAI>
 */
interface PhieuDkDetaiService extends IBaseService {

    public function getPaginate(FilterPhieuDkDetai $filter, string $page = 'page-1'): array;

    public function getList(FilterPhieuDkDetai $filter);

    public function store(array $data): PHIEU_DK_DETAI;

    public function delete(int $id): bool;

    public function printPhieuDkDeTai(int $id, array $data): string;

    /** chuẩn hóa dữ liệu biên tập viên trong phiếu DK đề tài
     * thêm giá trị idListBTV chứa các _id User BTV trong phiếu
     */
    public function convertDataListBTV();
    /** xét duyệt đề tài
     * @param int $id
     * @param int $phanCong
     * @return bool
     */
    public function xetDuyetDeTai(int $id, int $phanCong): PHIEU_DK_DETAI;

    public function xetDuyetNxbgdvn(int $id, int $idCanBo): PHIEU_DK_DETAI;

    public function previewMaSoNxbgd(int $idDeTai, bool $isMa12KiTu): string;

    public function capMaSoNxbgd(int $idDeTai, string $maSo, bool $isMa12KiTu, int $idCanBo): PHIEU_DK_DETAI;

    public function helperPrintPhieuDkDeTai(array $data): array;
}
