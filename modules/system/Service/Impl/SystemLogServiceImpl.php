<?php
namespace Modules\System\Service\Impl;

use Carbon\Carbon;
use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\SystemLogService;
use Modules\System\Repository\SystemLogRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\SYSTEMLOG;
use Modules\System\Object\FilterSystemLog;

class SystemLogServiceImpl extends BaseService implements SystemLogService
{
    /** @var SystemLogRepository */
    protected $baseRepo;

    public function __construct(SystemLogRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterSystemLog $filter, string $page = 'page-1') : array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 15,
            "page" => $page
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getList(FilterSystemLog $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function createLog(string $message) : SYSTEMLOG {
        /** @var SYSTEMLOG $systemLog */
        $systemLog = $this->baseRepo->create([
            "UserID" => Auth::user()->_id,
            "Desc" => $message,
            "IPAddress" => request()->ip() ?? "",
            "ActionTime" => Carbon::now(),
            "InUse" => true,
        ]);
        if(!$systemLog){
            throw new Exception("Lưu log thất bại");
        }
        return $systemLog;
    }

    public function store(array $data) : SYSTEMLOG {
        if(data_get($data, "id", 0) != 0) {
            /** @var SYSTEMLOG $systemLog */
            $systemLog = $this->baseRepo->get($data["id"]);
            if($systemLog) {
                $systemLog->update($data);
                return $systemLog;
            }
        }
        $systemLog = $this->baseRepo->create($data);
        if(!$systemLog){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $systemLog;
    }

    public function delete(int $id) : bool {
        $systemLog = $this->baseRepo->get($id);
        if(!$systemLog){
            throw new Exception("Hệ thống log không tồn tại");
        }
        $systemLog->InUse = 0;
        return $systemLog->save();
    }
}
