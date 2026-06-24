<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_NGOAI_NGU;
use Modules\System\Object\FilterNgoaingu;

/**
 * @extends IBaseService<DM_NGOAI_NGU>
 */
interface NgoaiNguService extends IBaseService {
    public function getPaginate(FilterNgoaingu $filter, string $page = 'page-1') : array;
    public function getList(FilterNgoaingu $filter);
    public function store(array $data) : DM_NGOAI_NGU;
    public function delete(int $id) : bool;
    
}
