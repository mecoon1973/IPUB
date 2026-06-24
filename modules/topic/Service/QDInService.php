<?php
namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\QDIn;
use Modules\Topic\Object\FilterQDIn;

/**
 * @extends IBaseService<QDIn>
 */
interface QDInService extends IBaseService {
    public function getPaginate(FilterQDIn $filter, string $page = 'page-1'): array;

    public function getList(FilterQDIn $filter);

    public function store(array $data): QDIn;

    public function delete(int $id): bool;
}
