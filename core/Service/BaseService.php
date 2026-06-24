<?php
namespace Core\Service;

use Core\Facade\Helper;
use Core\Model\Model;
use Core\Object\BaseObject;
use Core\Object\Paginate;
use Core\Object\PagiResult;
use Core\Object\PagiInfo;

use Core\Repository\BaseRepository;
use Core\Repository\CountersOlmRepository;

use Core\Service\CacheService;
use Core\Service\IBaseService;

/**
 * @template Type of Model
 * @implements IBaseService<Type>
 */
class BaseService implements IBaseService {
    protected $baseRepo;
    protected $counterRepo;
    protected $baseCache;
    protected $listCache;
    protected $paginationService;

    /**
     * @param ?BaseRepository<Type> $baseRepo
     */
    public function __construct($baseRepo = null) {
        $this->baseRepo = $baseRepo;
        /** @var CountersOlmRepository */
        $this->counterRepo = app(CountersOlmRepository::class);
    }

    public function count($conditions) {
        return $this->baseRepo->count($conditions);
    }

    public function get($id, $wrap = null, $paramsWrap = []) {
		$result = $this->baseCache->get($id);
        if($result && $wrap) {
            $result = $this->$wrap($result, $paramsWrap);
        }
        return $result;
    }

    //Thêm thuộc tính deleted = 1
    public function trash($id) {
        return $this->baseCache->trash($id);
    }

    //Thêm thuộc tính deleted = 0
    public function untrash($id) {
        return $this->baseCache->untrash($id);
    }

    public function updateById($id, $data, $options = []) {
        return $this->baseCache->updateById($id, $data, $options);
    }

    public function findOne($key, $conditions, $timed = 7200, $wrap = null, $paramsWrap = []) {
		if ($key == 'no-cache') {
			$result = $this->baseRepo->findOne($conditions);
        } else {
            $result = $this->baseCache->findOne($key, $conditions, $timed);
        }
        if($result && $wrap) {
            $result = $this->$wrap($result, $paramsWrap);
        }
        return $result;
	}

    public function countCache($key, $conditions, $timed) {
        return $this->baseCache->count($key, $conditions, $timed);
    }

    public function list($fields, $conditions, $pagination) {
        return $this->baseRepo->list($fields, $conditions, $pagination);
    }

    public function list4Cursor($fields, $filter, $pagination, $cursor, $forward) {
        return $this->baseRepo->list4Cursor($fields, $filter, $pagination, $cursor, $forward);
    }

    public function wrapList($input, $func, $param = null) {
        $result = $this->list($input['fields'], $input['conditions'], $input['pagination']);
        return $this->renderList($result, $func, $param);
    }

    public function renderList($data, $func, $param = null) {
        foreach ($data as $item) {
            if($param) {
                if(is_array($param)) {
                    $params = [$item];
                    array_push($params, $param);
                } else {
                    $params = [$item, $param];
                }
            } else {
                $params = $item;
            }
            $item = Helper::_call_user_func_custom($this, $func, $params);
        }
        return $data;
    }

    public function findAll($conditions, $sorted = [], $fields = [], $wrap = null, $params = []) {
        $listItems = $this->baseRepo->findAll($conditions, $sorted, $fields);
        if($wrap) {
            foreach ($listItems as $item) {
                $item = $this->$wrap($item, $params);
            }
        }
        return $listItems;
    }

    public function findAllAggregate($conditions, $sorted = [], $fields = [], $wrap = null, $params = []) {
        $listItems = $this->baseRepo->findAllAggregate($conditions, $sorted, $fields);
        if($wrap) {
            foreach ($listItems as $item) {
                $item = $this->$wrap($item, $params);
            }
        }
        return $listItems;
    }

    /**
     * Phân trang
     * @param Paginate $paginate Đối tượng phân trang
     * @param string $func Tên hàm xử lý dữ liệu
     * @param mixed $param Tham số tùy chọn
     * @return PagiResult Đối tượng kết quả phân trang
     */
    public function pagination(Paginate $paginate, $func = '', $param = null) {
        if ($paginate->useCursor) {
            return $this->cursorPagination($paginate, $func, $param);
        }
        $pagi = Helper::calculatorPagi($paginate);
        if ($func == '')
            $result = $this->list($paginate->fields, $paginate->conditions, $pagi->pagination);
        else
            $result = $this->wrapList(['fields'=>$paginate->fields, 'conditions'=>$paginate->conditions, 'pagination'=>$pagi->pagination], $func, $param);

        if ($paginate->loadRelations && method_exists($result, 'load')) {
            $result->load($paginate->loadRelations);
        }

        //Điều kiện tính tổng số bản ghi, đôi khi có thể truyền vào điều kiện khác với điều kiện tìm kiếm
        $countConditions = (isset($paginate->countConditions) && count($paginate->countConditions) > 0) ? $paginate->countConditions : $paginate->conditions;
        $total = $this->count($countConditions);
		$pagi_info = Helper::pagination($total, $pagi->pagination["limit"], $pagi->page, 3);

        return new PagiResult($result, $pagi_info);
    }

    /**
     * Phân trang cho dữ liệu tổng hợp
     * @param Paginate $paginate Đối tượng phân trang
     * @param string $func Tên hàm xử lý dữ liệu
     * @param mixed $param Tham số tùy chọn
     * @return PagiResult Đối tượng kết quả phân trang
     */
    public function paginationAggregate(Paginate $paginate, $func = '', $param = null) {
        $pagi = Helper::calculatorPagi($paginate);
        if ($func == '')
            $result = $this->baseRepo->listAggregate($paginate->fields, $paginate->conditions, $pagi->pagination);
        else {
            $result = $this->baseRepo->listAggregate($paginate->fields, $paginate->conditions, $pagi->pagination);
            $result = $this->renderList($result, $func, $param);
        }
        $total = $this->baseRepo->countAggregate($paginate->conditions);
		$pagi_info = Helper::pagination($total, $pagi->pagination["limit"], $pagi->page, 3);

        return new PagiResult($result, $pagi_info);
    }

    /**
     * Phân trang cho dữ liệu cursor-based
     * @param Paginate $paginate Đối tượng phân trang
     * @param string $func Tên hàm xử lý dữ liệu
     * @param mixed $param Tham số tùy chọn
     * @return PagiResult Đối tượng kết quả phân trang
     */
    public function cursorPagination(Paginate $paginate, $func = '', $param = null) {
        $conditions = $paginate->conditions;
        $pagination = [
            'limit' => $paginate->limit + 1,
            'sorted' => $paginate->cursorSorted,
            'adjacent' => true
        ];

        //TODO: Cần đánh compound index cho _id hợp lý trước mới thực thi if ở dưới
        // if (!in_array('_id', $paginate->cursorSorted)) {
        //     $pagination['sorted']['_id'] = 'asc';
        // }

        $cursor = $paginate->cursor ? core_decode_cursor($paginate->cursor) : [];

        /** @var \Illuminate\Support\Collection $result */
        if ($paginate->loadRelations) {
            $result = $this->baseRepo->list4Cursor($paginate->fields, $conditions, $pagination, $cursor, $paginate->forward)->load($paginate->loadRelations);
        } else {
            $result = $this->baseRepo->list4Cursor($paginate->fields, $conditions, $pagination, $cursor, $paginate->forward);
        }

        $hasExtra = $result->count() > $paginate->limit;
        $hasCursor = !empty($cursor);

        if ($paginate->forward) {
            $hasNextPage = $hasExtra;
            $hasPreviousPage = $hasCursor;
        } else {
            $hasNextPage = $hasCursor;
            $hasPreviousPage = $hasExtra;
        }

        if ($hasExtra) {
            $result->pop();
        }

        if(!$paginate->forward) {
            $result = $result->reverse();
        }

        $sortKey = array_key_first($pagination['sorted']);
        $startCursor = $endCursor = null;
        if (!$result->isEmpty()) {
            $firstItem = $paginate->forward ? $result[0] : $result[count($result) - 1];
            $lastItem = $paginate->forward ? $result[count($result) - 1] : $result[0];
            $startCursor = core_create_cursor($firstItem, $sortKey);
            $endCursor = core_create_cursor($lastItem, $sortKey);
        }

        if ($func)
	        $result = $this->renderList($result, $func, $param);

        return new PagiResult($result, new PagiInfo([
            'cursor_based' => true,
            'startCursor' => $startCursor,
            'endCursor' => $endCursor,
            'hasNextPage' => $hasNextPage,
            'hasPreviousPage' => $hasPreviousPage,
            'limit' => $paginate->limit
        ]));
    }

    public function inc($condition, $key, $inc) {
        return $this->baseRepo->inc($condition, $key, $inc);
    }

    public function bulkWrite($bulk, $options = []) {
        return $this->baseRepo->bulkWrite($bulk, $options);
    }

}
