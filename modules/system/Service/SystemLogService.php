<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\SYSTEMLOG;
use Modules\System\Object\FilterSystemLog;

/**
 * @extends IBaseService<SYSTEMLOG>
 */
interface SystemLogService extends IBaseService {
    public function getPaginate(FilterSystemLog $filter, string $page = 'page-1') : array;
    public function getList(FilterSystemLog $filter);
    public function createLog(string $message) : SYSTEMLOG;
    public function store(array $data) : SYSTEMLOG;
    public function delete(int $id) : bool;
}
