<?php
namespace Core\Service;

use Core\Model\Model;
use Core\Object\Paginate;
use Core\Utility\Collection;

/**
 * @template Type of Model
 */
interface IBaseService {
    
    /**
     * @return int
     */
    public function count($conditions);

    /**
     * @return ?Type
     */
    public function get($id, $wrap = null, $paramsWrap = []);
    
    /**
     * 
     */
    public function trash($id);
    
    /**
     * 
     */
    public function untrash($id);
    
    /**
     * 
     */
    public function updateById($id, $data, $options = []);
    
    /**
     * @return ?Type
     */
    public function findOne($key, $conditions, $timed = 0, $wrap = null, $paramsWrap = []);
    
    /**
     * 
     */
    public function countCache($key, $conditions, $timed);

    /**
     * @return Collection<Type>
     */
    public function list($fields, $conditions, $pagination);

    /**
     * @return Collection<Type>
     */
    public function list4Cursor($fields, $filter, $pagination, $cursor, $forward);

    /**
     * 
     */
    public function wrapList($input, $func, $param = null);

    /**
     * @return Collection<Type>
     */
    public function findAll($conditions, $sorted = [], $fields = [], $wrap = null, $params = []);
    public function findAllAggregate($conditions, $sorted = [], $fields = [], $wrap = null, $params = []);

    public function pagination(Paginate $paginate, $func = '', $param = null);

    public function paginationAggregate(Paginate $paginate, $func = '', $param = null);
}