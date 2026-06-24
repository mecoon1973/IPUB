<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_DOITUONG_SNV;
use Modules\System\Object\FilterDoituongSNV;

/**
 * @extends IBaseService<DM_DOITUONG_SNV>
 */
interface DoituongSNVService extends IBaseService {
    public function getPaginate(FilterDoituongSNV $filter, string $page = 'page-1'): array;
    public function getList(FilterDoituongSNV $filter);
    public function store(array $data): DM_DOITUONG_SNV;
    public function delete(int $id): bool;
    public function convertDataDoituongSNV(): void;
}
