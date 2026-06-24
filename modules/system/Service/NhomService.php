<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_NHOM;
use Modules\System\Object\FilterNhom;

/**
 * @extends IBaseService<DM_NHOM>
 */
interface NhomService extends IBaseService {

    /** api lấy danh sách nhóm
    * @return array<DM_NHOM>
    */
    public function getAllNhom(FilterNhom $filter);

    /** api lấy danh sách nhóm
    * @return array<DM_NHOM>
    */
    public function getListNhom(FilterNhom $filter, string $page);

    /** api thêm mới hoặc cập nhật nhóm
    * @param DM_NHOM $nhom
    * @return DM_NHOM
    */
    public function store(array $data);

    /** api thêm cán bộ vào nhóm
    * @param int $id
    * @param array<int> $listIdUser
    * @return void
    */
    public function addCanboToNhom(int $id, array $listIdUser);

    /** api xóa nhóm
    * @param int $id
    * @return void
    */
    public function delete(int $id);

    public function convertDataNhomQuyen(): void;

}
