<?php

namespace Modules\Topic\Repository;

use Core\Repository\IBaseRepository;
use Modules\Topic\Model\NX_CANBO_DETAI;

/**
 * @extends IBaseRepository<NX_CANBO_DETAI>
 */
interface NX_CanboDetaiRepository extends IBaseRepository
{
    /** @return int[] */
    public function getActivePhanCongDeTaiIds(): array;

    /** @return int[] */
    public function getActivePhanCongDeTaiIdsByCanBo(int $idCanBo): array;
}
