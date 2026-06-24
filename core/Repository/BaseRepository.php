<?php
namespace Core\Repository;

use Carbon\Carbon;
use Core\Facade\Helper;
use Core\Object\Paginate;
use Core\Object\PagiResult;
use Core\Repository\IBaseRepository;

use \Illuminate\Database\Eloquent\Builder as QueryBuilder;
use \Illuminate\Support\Facades\DB;
use MongoDB\Laravel\Eloquent\Builder;

use Core\Model\Model;
use Core\Object\BaseObject;
use Illuminate\Support\Facades\Auth;
use MongoDB\Collection as MongoDBCollection;

use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use Symfony\Polyfill\Intl\Idn\Resources\unidata\Regex;

/**
 * @template Type of Model
 * @implements IBaseRepository<Type>
 */
abstract class BaseRepository implements IBaseRepository {
	/**
	 * @var Type
	 */
	protected $_model;

	public function __construct() {
		$this->setModel();
	}

	abstract public function getModel();

	/**
	 * Set model
	 */
	public function setModel() {
        $this->_model = app()->make(
            $this->getModel()
        );
    }

	/**
	 * Get model
	 */
	public function _getModel() {
		return $this->_model;
	}

	/**
	 * @return Builder
	 */
	public function _getPrimaryBuilder() {
		$conn = DB::connection(config("database.mongo_primary_connection", "olm"));
		return $conn->collection($this->_model->getTable());
	}

	/**
	 * Xử lý điều kiện truy vấn cho cursor-based pagination
	 *
	 * Hàm này xử lý các điều kiện truy vấn cho phân trang dựa trên cursor, bao gồm:
	 * - Xử lý điều kiện "after" và "before" cho trường _id
	 * - Xử lý điều kiện sắp xếp cho trường khác _id nếu được chỉ định
	 * - Loại bỏ các điều kiện đã xử lý khỏi mảng condition
	 *
	 * @param array &$condition Mảng chứa các điều kiện truy vấn, được truyền bằng tham chiếu
	 * @param Builder &$query Query builder của MongoDB, được truyền bằng tham chiếu
	 * @param array $pagination Thông tin phân trang (mặc định là mảng rỗng)
	 */
	protected function executeAfterBeforeQuery(&$condition, &$query, $pagination = []) {
		if(array_key_exists("after", $condition) || array_key_exists("before", $condition)) {
			$query = $this->buildQueryAfterBefore("_id",  $condition, $query);
			if(isset($condition["sorted_key"])) {
				if($condition["sorted_key"] != "_id") {
					$query = $this->buildQueryAfterBefore($condition["sorted_key"], $condition, $query, $condition["sorted_value"], $pagination);
					if (isset($condition['after'])) {
						$query = $query->orderBy($condition["sorted_key"], -1);
					} elseif (isset($condition['before'])) {
						$query = $query->orderBy($condition["sorted_key"], 1);
					}
				}
			}
			unset($condition["after"]);
			unset($condition["before"]);
		}
		unset($condition["sorted_key"]);
		unset($condition["sorted_value"]);
	}

	/**
	 * Xây dựng truy vấn cho cursor-based pagination dựa trên điều kiện before/after
	 *
	 * Hàm này xử lý việc xây dựng truy vấn MongoDB cho phân trang dựa trên cursor, bao gồm:
	 * - Xác định hướng sắp xếp (tăng/giảm) dựa trên điều kiện after/before và cấu hình pagination
	 * - Xử lý giá trị cursor cho trường _id và các trường khác
	 * - Tạo điều kiện truy vấn tương ứng ($gt/$gte hoặc $lt/$lte)
	 *
	 * @param string $primaryKey Tên trường làm khóa phân trang (VD: _id)
	 * @param array $condition Mảng chứa điều kiện truy vấn (after/before)
	 * @param Builder $query Query builder của MongoDB
	 * @param mixed $value Giá trị cursor tùy chọn (mặc định null)
	 * @param array $pagination Thông tin phân trang (mặc định rỗng)
	 * @return Builder Query builder đã được thêm điều kiện
	 */
	protected function buildQueryAfterBefore($primaryKey, $condition, $query, $value = null, $pagination = []) {
		if(isset($condition['before']) || isset($condition['after'])) {
			$isAsc = isset($condition['after']) ? true : false;
			if($pagination && isset($pagination["sorted"][$primaryKey])) {
				$isAsc = $pagination["sorted"][$primaryKey] == "asc" ? true : false;
			}
			if ($isAsc) {
				if(!$value) {
					$value = is_numeric($condition['after']) ? $condition['after'] : new ObjectId($condition['after']);
				}
				$query = $query->where($primaryKey,[($primaryKey == "_id" ? '$gt' : '$gte') => $value]);
			} else {
				if(!$value) {
					$value = is_numeric($condition['before']) ? $condition['before'] : new ObjectID($condition['before']);
				}
				$query = $query->where($primaryKey,[($primaryKey == "_id" ? '$lt' : '$lte') => $value]);
			}
		}
		return $query;
	}

    /**
     * Xây dựng truy vấn MongoDB từ điều kiện và thông tin phân trang
     *
     * Hàm này tạo và trả về một query builder với các điều kiện lọc được áp dụng:
     * - Khởi tạo query builder mới từ model
     * - Xử lý các điều kiện cursor-based pagination (after/before)
     * - Xử lý các điều kiện lọc thông thường:
     *   + Hỗ trợ regex cho tìm kiếm chuỗi
     *   + So sánh bằng cho các điều kiện khác
     *
     * @param array $condition Mảng chứa các điều kiện lọc
     * @param array $pagination Thông tin phân trang (mặc định rỗng)
     * @return Builder Query builder đã được áp dụng điều kiện
     */
    public function buildQuery($condition, $pagination = []) {
        $query = $this->_getModel()->newQuery();
		$this->executeAfterBeforeQuery($condition, $query, $pagination);
        foreach ($condition as $key => $value) {
			if(is_array($value) && isset($value['$regex']))
				$query = $query->where($key, 'regexp', $value['$regex']);
			else
				$query = $query->where($key,'=',$value);
		}
		return $query;
	}

	/**
	 * Tạo các tùy chọn cho truy vấn MongoDB dựa trên thông tin phân trang
	 *
	 * Hàm này áp dụng các tùy chọn phân trang và sắp xếp cho query builder:
	 * - Thêm skip để bỏ qua số bản ghi
	 * - Thêm limit để giới hạn số bản ghi trả về
	 * - Thêm sắp xếp theo các trường được chỉ định
	 *
	 * @param Builder $query Query builder cần thêm tùy chọn
	 * @param array $pagination Mảng chứa thông tin phân trang:
	 *   - skip: Số bản ghi cần bỏ qua
	 *   - limit: Số bản ghi tối đa trả về ('all' để lấy tất cả)
	 *   - sorted: Mảng chứa thông tin sắp xếp theo trường
	 * @return Builder Query builder đã được thêm tùy chọn
	 */
	public function createOption($query, $pagination) {
		if (array_key_exists('skip', $pagination))
			$query = $query->skip($pagination['skip']);
		if (array_key_exists('limit', $pagination) && $pagination['limit'] != 'all')
			$query = $query->take($pagination['limit']);

		if (array_key_exists('sorted', $pagination))
			foreach ($pagination['sorted'] as $key => $value) {
				if(is_string($value)) {
					$value = $value == "desc" ? -1 : 1;
				}
				$query = $query->orderBy($key, $value);
			}
		return $query;
	}

	/**
	 * Lấy một bản ghi dựa trên ID
	 *
	 * @param mixed $_id ID của bản ghi
	 * @param array $fields Các trường cần lấy
	 * @return Model|null Bản ghi tìm thấy hoặc null nếu không tìm thấy
	 */
    public function get($_id, $fields = []) {
		return $this->_model::where('_id','=',$_id)->first($fields);
	}

	/**
	 * Tìm kiếm một bản ghi dựa trên điều kiện và các trường cần lấy
	 *
	 * @param array $condition Điều kiện tìm kiếm
	 * @param array $fields Các trường cần lấy
	 * @param array $sorted Mảng chứa thông tin sắp xếp theo trường
	 * @return Model|null Bản ghi tìm thấy hoặc null nếu không tìm thấy
	 */
	public function findOne($condition, $fields = [], $sorted = []) {
		$query = self::buildQuery($condition);
		if (count($sorted) > 0)
			$query = self::createOption($query, ["sorted" => $sorted]);
		return $query->first($fields);
	}

	/**
	 * Thực hiện phân trang dựa trên điều kiện và các trường cần lấy
	 *
	 * @param array $fields Các trường cần lấy trong kết quả
	 * @param array $condition Điều kiện tìm kiếm
	 * @param array $pagination Thông tin phân trang bao gồm sorted (sắp xếp), skip, limit
	 * @return \Illuminate\Support\Collection Tập kết quả
	 */
	public function list($fields, $condition, $pagination) {
		$query = self::buildQuery($condition, $pagination);
		$query = self::createOption($query, $pagination);
		return $query->get($fields);
	}


	/**
	 * Lấy ngẫu nhiên một số lượng bản ghi từ collection MongoDB
	 *
	 * @param array $fields Các trường cần lấy trong kết quả
	 * @param array $conditions Điều kiện lọc dữ liệu
	 * @param int $limit Số lượng bản ghi cần lấy
	 */
	public function listRandom($fields, $conditions, $limit) {
        $query = $this->_model::raw(function($collection) use ($fields, $conditions, $limit) {
            return $collection->aggregate([
                [ '$match' => $conditions ],
                [ '$sample' => ["size" => $limit] ]
            ]);
        });
		return $query;
	}

	/**
	 * Thực hiện phân trang dựa trên cursor cho collection MongoDB
	 *
	 * @param array $fields Các trường cần lấy trong kết quả
	 * @param array $filter Điều kiện lọc dữ liệu
	 * @param array $pagination Thông tin phân trang bao gồm sorted (sắp xếp), skip, limit
	 * @param array $cursor Giá trị cursor để xác định vị trí bắt đầu lấy dữ liệu
	 * @param bool $forward Hướng lấy dữ liệu (true: tiến, false: lùi)
	 * @return \Illuminate\Support\Collection Tập kết quả
	 */
	public function list4Cursor($fields, $filter, $pagination, $cursor = [], $forward = true) {
		/** @var QueryBuilder $query */
        $query = $this->_model->newQuery();
        foreach ($filter as $key => $value) {
			if(is_array($value) && isset($value['$regex']))
				$query = $query->where($key, 'regexp', new Regex($value['$regex']));
			else
				$query = $query->where($key,'=',$value);
		}

		//Handle Cursor:
		$array = $pagination["sorted"];
		//Khi _id là string, sort gây chậm, do không có điều kiện để compound index quá nhiều trường hợp, đề xuất xóa sort _id
		if (isset($array['id']) && $array['id'] == "desc") {
			unset($pagination["sorted"]['_id']);
			unset($array['_id']);
		}

		if (count($cursor)) {
			$query = $query->where(function(QueryBuilder $query) use ($array, $cursor, $forward) {
				while (count($array)) {
					$fDirection = end($array);
					$fSorted = key($array);
					unset($array[$fSorted]);

					$query = $query->orWhere(function(QueryBuilder $query) use ($fDirection, $fSorted, $array, $cursor, $forward) {
						foreach($array as $sorted => $direction) {
							if (isset($cursor[$sorted]))
								$query = $query->where($sorted, "=", $cursor[$sorted]);
						}

						$isAsc = $fDirection == "asc";
						$gt = ($forward == $isAsc);

						if (isset($cursor[$fSorted]))
							$query = $query->where($fSorted, $gt ? ">" : "<", $cursor[$fSorted]);
						return $query;
					});
				}
				return $query;
			});
		}

		//Handle skip/limit & sort:
		if (isset($pagination['adjacent']) && !$forward) {
			//Mục đích lấy ra đúng các bản ghi liên kề cursor khi backward
			foreach ($pagination['sorted'] as $key => $direction) {
				if ($key !== '_id') {
					$pagination['sorted'][$key] = $direction === 'asc' ? 'desc' : 'asc';
				}
			}
		}

		$query = self::createOption($query, $pagination);
		return $query->get($fields);
	}

	public function findAllWithFilter(BaseObject $filterObject, $sorted = [], $fields = []) {
        $condition = $filterObject->buildConditions();
        $fieldsObject = count($fields) > 0 ? $fields : $filterObject->fields;
        $query = $this->findAllWithQuery($condition, $sorted, $filterObject->limit);
        if ($filterObject && $filterObject->relations !== []) {
            $query = $query->with($filterObject->relations);
        }
        return $query->get($fieldsObject);
    }

	public function findAll($condition = [], $sorted = [], $fields = []) {
        $query = $this->findAllWithQuery($condition, $sorted);
        return $query->get($fields);
    }

	/**
	 * Tạo truy vấn tìm kiếm tất cả các bản ghi dựa trên điều kiện và sắp xếp
	 *
	 * @param array $condition Điều kiện tìm kiếm
	 * @param array $sorted Sắp xếp theo trường nào
	 * @param int $limit Số lượng bản ghi tối đa trả về
	 * @return Builder Trả về truy vấn tìm kiếm
	 */
	public function findAllWithQuery($condition = [], $sorted = [], $limit = 1500) {
		$query = $this->_model->newQuery();
		if (count($condition) > 0)
			$query = self::buildQuery($condition);
		$options = ["limit"=>$limit];
		if (count($sorted) > 0) {
			$options["sorted"] = $sorted;
		}
		$query = self::createOption($query, $options);
		return $query;
	}

	public function count($condition) {
		if (count($condition) > 0)
			return self::buildQuery($condition)->count();
		return $this->_model::count();
	}

    /** nếu có thuộc tính timestamps2 = true thì sẽ gán giá trị mặc định CreateOn và EditedOn ở model */
    public function setDefaultTimestamp($data, $isUpdate=false) {
        /** @var User $auth */
        $auth = Auth::user();
        if($auth) {
            if(!$isUpdate) {
                $data['CreateBy'] = $auth->id;
            }
            $data['EditedBy'] = $auth->id;
        }
        if(isset($this->_model->timestamps2) && $this->_model->timestamps2) {
            if(!$isUpdate) {
                $data['CreateOn'] = Carbon::now('UTC');
            }
            $data['EditedOn'] = Carbon::now('UTC');
        }
        return $data;
    }

    /**
     * Tạo mới một bản ghi
     * nếu trong model có thuộc tính incrementing = true thì sẽ tạo mới một bản ghi với primaryKey tự động tăng
     * sử dụng repository CountersOlmRepository để tạo id tự động
     * @param array $data Dữ liệu của bản ghi
     * @return Model Bản ghi đã được tạo
     */
	public function create($data) {
        $data = $this->setDefaultTimestamp($data);
        if(isset($this->_model->incrementing) && $this->_model->incrementing) {
            /** @var CountersOlmRepository */
            $countRepo = app(CountersOlmRepository::class);
            return $countRepo->retry($this->_model->getTable(), function($id) use ($data) {
                $data[$this->_model->primaryKey ?? "_id"] = $id;
                return $this->_model::create($data);
            });
        }else{
            return $this->_model::create($data);
        }
	}

	public function updateOne($_id, $data, $options = []) {
        $data = $this->setDefaultTimestamp($data, true);
		$model = $this->get($_id);
		if (!$model) {
			return false;
		}
		return $model->update($data, $options);
	}

	public function update($condition, $data, $options = []) {
        $data = $this->setDefaultTimestamp($data, true);
		return self::buildQuery($condition)->update($data, $options);
	}

	public function upsert($condition, $data, $createdField = 'created', $updatedField = 'updated') {
		$row = $this->findOne($condition);
		$data = $this->setDefaultTimestamp($data, true);
        if(empty($row))
			return $this->create($data);
		if (isset($data[$createdField])) {
			$data[$updatedField] = $data[$createdField];
			unset($data[$createdField]);
		}
		return $row->update($data);
	}

	public function trash($_id) {
		return $this->updateOne($_id, ['deleted' => 1]);
	}

	public function untrash($_id) {
		return $this->updateOne($_id, ['deleted' => 0]);
	}

	public function deleteOne($_id, $forever = true) {
		$nameCollection = $this->_getModel()->getTable();
		if ($forever && !in_array($nameCollection, config('trash.collection')))
			return $this->get($_id)->delete();
		return $this->updateOne($_id, ['deleted'=>10, 'user_deleted'=>Auth::user()->_id]);
	}

    public function delete($condition, $forever = true) {
		$nameCollection = $this->_getModel()->getTable();
		if ($forever && !in_array($nameCollection, config('trash.collection')))
        	return self::buildQuery($condition)->delete();
		return $this->update($condition, ['deleted'=>10, 'user_deleted'=>Auth::user()->_id]);
    }

	public function inc($condition, $key, $inc) {
		/** @var QueryBuilder */
		$query = is_array($condition) ? self::buildQuery($condition) : $this->_model::where('_id','=',$condition);
		return $query->increment($key, $inc);
	}

	public function sum($condition, $sum) {
		return self::buildQuery($condition)->sum($sum);
	}

    public function groupBy(Paginate $paginate, $group, $page=true){
		$pagi = Helper::calculatorPagi($paginate);
        $query = $this->_model::raw(function($collection) use ($paginate, $group, $pagi) {
			$group = $this->attributeToFieldsGroup($this->_model->attributesToArray(), $group);
            return $collection->aggregate([
                [ '$match' => $paginate->conditions ],
                [ '$sort' => $pagi->pagination["sorted"] ],
                [ '$group' => $group ],
                [ '$limit' => $pagi->pagination["limit"] ],
                [ '$skip' => $pagi->pagination["skip"] ],
            ]);
        });

		if($page){
			$count = $this->_model::raw(function($collection) use ($paginate, $group, $pagi) {
				$group = $this->attributeToFieldsGroup($this->_model->attributesToArray(), $group);
				return $collection->aggregate([
					[ '$match' => $paginate->conditions ],
					[ '$group' => $group ],
					[ '$count' => "count" ]
				]);
			});
			$total = $count->all()[0]->count ?? 0;
			$pagi_info = Helper::pagination($total, $pagi->pagination["limit"], $pagi->page, 3);
			return new PagiResult($query->all(), $pagi_info);
		}else{
			return $query->all();
		}
    }

	protected function attributeToFieldsGroup(array $attributes, $group) {
		foreach($attributes as $field => $dfValue) {
			if(!isset($group[$field])) {
				$group[$field] = ['$first' => "$".$field];
			}
		}
		return $group;
	}

	public function createIndex($key, array $options = []) {
		return $this->_getModel()->raw(function($collection) use ($key, $options) {
			/** @var MongoDBCollection $collection */
			return $collection->createIndex($key, $options);
		});
	}

	public function listIndexes(array $options = []) {
		return $this->_getModel()->raw(function($collection) use ($options) {
			/** @var MongoDBCollection $collection */
			return $collection->listIndexes($options);
		});
	}

	public function addToSetArray($condition, $field, $array) {
		return $this->update($condition, ['$addToSet' => [$field => ['$each' => $array]]]);
	}

	public function addToSet($condition, $field, $value) {
		return $this->update($condition, ['$addToSet' => [$field => $value]]);
	}

	protected $listConditionAggregate = [];

	/**
	 * Dùng khi muốn sử dụng aggregate để truy vấn
	 * Trong repo:
	 * 	Khai báo listConditionAggregate ví dụ: ["prepaid.vip_expired_date"]
	 * 	Định nghĩa hàm getAggregate($condition) để trả về mảng aggregate: Xem ví dụ CareTrackingDetailRepositoryImpl
	 *
	 * */
    public function listAggregate($fields, $condition, $pagination) {
        $keyConditions = array_keys($condition);
        if(array_intersect($this->listConditionAggregate, $keyConditions)) {
            $query = $this->_model::raw(function($collection) use ($condition, $keyConditions, $pagination) {
                $aggregate = $this->getAggregate($condition);
				$aggregate = $this->handlePipelineAggregate($aggregate, $condition);
                $aggregate = array_merge($aggregate, [
                    [
                        '$skip' => $pagination['skip']
                    ],[
                        '$limit' => $pagination['limit']
                    ],[
                        '$sort' => $pagination['sorted']
                    ]
                ]);
				// dd($aggregate, json_encode($aggregate));
                return $collection->aggregate($aggregate);
            });
            return $query;
        } else {
            return self::list($fields, $condition, $pagination);
        }
    }

    public function findAllAggregate($condition, $sorted = [], $fields = []) {
        $keyConditions = array_keys($condition);
        // dd($keyConditions, array_intersect(["prepaid.vip_expired_date"], $keyConditions), $condition);
        if(array_intersect($this->listConditionAggregate, $keyConditions)) {
            $query = $this->_model::raw(function($collection) use ($condition, $sorted) {
                $aggregate = $this->getAggregate($condition);
				$aggregate = $this->handlePipelineAggregate($aggregate, $condition);
				if($sorted) {
					$aggregate[] = [
                        '$sort' => $sorted
					];
				}
                return $collection->aggregate($aggregate);
            });
            return $query;
        } else {
            return self::findAll($condition, $sorted, $fields);
        }
    }

    public function countAggregate($condition) {
        $keyConditions = array_keys($condition);
        if(array_intersect($this->listConditionAggregate, $keyConditions)) {
            $count = $this->_model::raw(function($collection) use ($condition) {
                $aggregate = $this->getAggregate($condition);
				$aggregate = $this->handlePipelineAggregate($aggregate, $condition);
                $aggregate = array_merge($aggregate, [
                    [
                        '$count' => "count"
                    ]
                ]);
                return $collection->aggregate($aggregate);
            });
            $count = $count[0]->count ?? 0;
        } else {
            $count = self::count($condition);
        }
        return $count;
    }

	private function handlePipelineAggregate($pipeline, $conditions) {
		// move all condition in $this->listConditionAggregate to after execute $pipeline, the other conditions will be before execute $pipeline
		$conditionAggregate = [];
		$conditionNormal = [];
		foreach($conditions as $key => $value) {
			if(in_array($key, $this->listConditionAggregate)) {
				$conditionAggregate[$key] = $value;
			} else {
				$conditionNormal[$key] = $value;
			}
		}
		$aggregate = [];
		if(count($conditionNormal) > 0) {
			$aggregate[] = [
				'$match' => $conditionNormal
			];
		}
		$aggregate = array_merge($aggregate, $pipeline);
		if(count($conditionAggregate) > 0) {
			$aggregate[] = [
				'$match' => $conditionAggregate
			];
		}
		return $aggregate;
	}

    protected function getAggregate(&$condition) {
        $aggregate = [];
        return $aggregate;
    }

	public function aggregate(array $pipeline, array $options = [])
	{
		return $this->_getModel()->raw(function($collection) use ($pipeline, $options) {
			/** @var MongoDBCollection $collection */
			return $collection->aggregate($pipeline, $options);
		});
	}

	/**
	 * Thực hiện bulkWrite với giới hạn số lượng operations mỗi lần,
	 * Sử dụng khi cập nhật nhiều các giá trị khác nhau cho nhiều record của 1 collection
	 *
	 * @param array $bulk    Danh sách operations (updateOne, insertOne, deleteOne, ...)
	 * @param array $options Tùy chọn bulkWrite (ordered, writeConcern, ...)
	 * @param int   $limit   Số lượng operation tối đa cho mỗi lần bulkWrite
	 *
	 * @return array Kết quả từ tất cả các batch
	 */
	public function bulkWrite(array $bulk, array $options = [], int $limit = 1000) {
		if (empty($bulk)) return [];
		$results = [];
		foreach (array_chunk($bulk, $limit) as $chunk) {
			$result = $this->_getModel()->raw(function($collection) use ($chunk, $options) {
				return $collection->bulkWrite($chunk, $options);
			});
			$results[] = $result;
		}
		return $results;
	}

}
