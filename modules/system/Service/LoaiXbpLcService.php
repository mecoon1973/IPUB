<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_LOAI_XBP_LC;
use Modules\System\Object\FilterLoaiXbpLc;

/**
 * @extends IBaseService<DM_LOAI_XBP_LC>
 */
interface LoaiXbpLcService extends IBaseService {
    public function getPaginate(FilterLoaiXbpLc $filter, string $page): array;
    public function getList(FilterLoaiXbpLc $filter);
    public function store(array $data) : DM_LOAI_XBP_LC;
    public function delete(int $id): bool;
}
