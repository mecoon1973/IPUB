<?php

namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\FilterHDXBNXBGDVN;

/**
 * @extends IBaseService<PHIEU_DK_DETAI>
 */
interface HDXBNXBGDVNService extends IBaseService {
    public function getPaginate(FilterHDXBNXBGDVN $filter, string $page = 'page-1'): array;

    public function getList(FilterHDXBNXBGDVN $filter);

    /** @param int[] $idsDeTai */
    public function phanCongDocDuyet(array $idsDeTai, int $idCanBo): int;
}
