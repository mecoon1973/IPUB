<?php
namespace Core\Repository;

use Core\Model\Model;
use Core\Object\BaseObject;
use Core\Utility\Collection;

use MongoDB\Laravel\Eloquent\Builder;
use MongoDB\Collection as MongoDBCollection;
use MongoDB\Model\IndexInfoIterator;

/**
 * @template Type of Model
 * ```php
 * //@extends IBaseRepository<ModelType>
 * interface ModelTypeRepository extends IBaseRepository {};
 * ```
 */
interface IBaseRepository {

	/**
	 *
	 */
	public function _getModel();

	/**
	 * @return Builder
	 */
	public function _getPrimaryBuilder();

	/**
	 * @return void
	 */
	public function setModel();

	/**
	 * @return Builder
	 */
	public function buildQuery($condition, $pagination = []);

	/**
	 * @return ?Type
	 */
	public function get($_id, $fields = []);

	/**
	 * @return ?Type
	 */
	public function findOne($condition, $fields = [], $sorted = []);

	/**
	 * @return Collection<Type>
	 */
	public function list($fields, $condition, $pagination);

	/**
	 * @return Collection<Type>
	 */
	public function list4Cursor($fields, $filter, $pagination, $cursor = [], $forward = true);

	/**
	 * @return Collection<Type>
	 */
	public function findAll($condition = [], $sorted = [], $fields = []);

	/**
	 * @return Collection<Type>
	 */
	public function findAllWithFilter(BaseObject $fieldsObject, $sorted = [], $fields = []);

	/**
	 * @return int
	 */
	public function count($condition);

	/**
	 * @return Type
	 */
	public function create($data);

	public function updateOne($_id, $data, $options = []);

	/**
	 * @return bool
	 */
	public function update($condition, $data, $options = []);

	/**
	 * @return bool|Type
	 */
	public function upsert($condition, $data, $createdField = 'created', $updatedField = 'updated');

	/**
	 * @deprecated Use SoftDelete instead
	 * @return bool
	 */
	public function trash($_id);

	/**
	 * @deprecated Use SoftDelete instead
	 * @return bool
	 */
	public function untrash($_id);

	/**
	 * @deprecated Use SoftDelete instead
	 * @return ?bool
	 */
	public function deleteOne($_id, $forever = false);

	/**
	 *
	 */
	public function delete($condition, $forever = false);

	/**
	 * @return int
	 */
	public function inc($condition, $key, $inc);

	/**
	 * @return int
	 */
	public function sum($condition, $sum);

	/**
     * Create a single index for the collection.
     *
	 * @see MongoDBCollection::createIndex() for supported options
     * @param array|object $key     Document containing fields mapped to values,
     *                              which denote order or an index type
     * @param array        $options Index and command options
     * @return string The name of the created index
	 */
	public function createIndex($key, array $options = []);

	/**
     * Returns information for all indexes for the collection.
     *
	 * @see MongoDBCollection::listIndexes() for supported options
     * @param array $options
	 * @return IndexInfoIterator
	 */
	public function listIndexes(array $options = []);

	public function listAggregate($fields, $condition, $pagination);
	public function findAllAggregate($condition, $sorted = [], $fields = []);
	public function countAggregate($condition);

	/**
	 * Tạo truy vấn tìm kiếm tất cả các bản ghi dựa trên điều kiện và sắp xếp
	 *
	 * @param array $condition Điều kiện tìm kiếm
	 * @param array $sorted Sắp xếp theo trường nào
	 * @param int $limit Số lượng bản ghi tối đa trả về
	 * @return Builder Trả về truy vấn tìm kiếm
	 */
	public function findAllWithQuery($condition = [], $sorted = [], $limit = 1500);
	public function bulkWrite(array $bulk, array $options = [], int $limit = 1000);
}
