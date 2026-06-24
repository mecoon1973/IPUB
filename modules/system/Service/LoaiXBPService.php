<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_LOAI_XBP;
use Modules\System\Object\FilterLoaiXBP;

/**
 * @extends IBaseService<DM_LOAI_XBP>
 */
interface LoaiXBPService extends IBaseService {
    public function getPaginate(FilterLoaiXBP $filter, string $page): array;
    public function getList(FilterLoaiXBP $filter);
    public function store(array $data) : DM_LOAI_XBP;
    public function delete(int $id): bool;
}
