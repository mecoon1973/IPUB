<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_DONVILC;
use Modules\System\Object\FilterDonviLC;

/**
 * @extends IBaseService<DM_DONVILC>
 */
interface DonviLCService extends IBaseService {
    public function getPaginate(FilterDonviLC $filter, string $page = 'page-1'): array;
    public function getList(FilterDonviLC $filter);
    public function store(array $data): DM_DONVILC;
    public function delete(int $id): bool;
    public function convertDataCTDonviLcLoaiXbpLc(): void;
}
