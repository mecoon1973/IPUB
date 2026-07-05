<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Model\NX_CANBO_DETAI;
use Modules\Topic\Repository\NX_CanboDetaiRepository;

class NX_CanboDetaiRepositoryImpl extends BaseRepository implements NX_CanboDetaiRepository
{
    public function getModel()
    {
        return NX_CANBO_DETAI::class;
    }

    public function getActivePhanCongDeTaiIds(): array
    {
        $rows = $this->findAll([
            'LaPhanCong' => true,
            'IsDeleted' => false,
            'InUsed' => true,
        ], [], ['ID_DeTai']);

        $ids = [];
        foreach ($rows as $row) {
            $idDeTai = (int) ($row->ID_DeTai ?? 0);
            if ($idDeTai > 0) {
                $ids[] = $idDeTai;
            }
        }

        return array_values(array_unique($ids));
    }
}
