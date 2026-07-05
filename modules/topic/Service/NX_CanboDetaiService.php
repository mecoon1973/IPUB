<?php

namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\NX_CANBO_DETAI;

/**
 * @extends IBaseService<NX_CANBO_DETAI>
 */
interface NX_CanboDetaiService extends IBaseService
{
    /**
     * @param int[] $idsDeTai
     * @return array<int, NX_CANBO_DETAI> map ID_DeTai => bản ghi phân công
     */
    public function getActivePhanCongMapByDeTaiIds(array $idsDeTai): array;

    /** @return int[] */
    public function getActivePhanCongDeTaiIds(): array;

    /**
     * Phân công đọc duyệt đề tài.
     *
     * @param int[] $idsDeTai
     */
    public function phanCongDocDuyet(array $idsDeTai, int $idCanBoDoc, int $idCanBoPhanCong): int;
}
