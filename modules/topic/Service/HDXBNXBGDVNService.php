<?php

namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\FilterHDXBNXBGDVN;
use Modules\Topic\Object\FilterPheDuyetDiIn;

/**
 * @extends IBaseService<PHIEU_DK_DETAI>
 */
interface HDXBNXBGDVNService extends IBaseService {
    public function getPaginate(FilterHDXBNXBGDVN $filter, string $page = 'page-1'): array;

    public function getList(FilterHDXBNXBGDVN $filter);

    /** @param int[] $idsDeTai */
    public function phanCongDocDuyet(array $idsDeTai, int $idCanBo): int;

    public function getPaginatePheDuyetDiIn(FilterPheDuyetDiIn $filter, string $page = 'page-1'): array;

    /** @param array<int, array<string, mixed>> $items */
    public function luuPheDuyetDiIn(array $items, int $idCanBo): int;
}
