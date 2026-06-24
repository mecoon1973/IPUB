<?php
namespace Core\Model;

use Core\Model\Model;

/**
 * @property int $seq
 */
class CountersOlm extends Model {
	protected $connection = 'olm_primary';
	protected $collection = 'counters';
	public $timestamps = false;

	protected $fillable = [
		'_id', 'seq',
	];
}
