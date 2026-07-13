<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\TemplateExportRepository;
use Modules\System\Model\DM_TEMPLATE_EXPORT;


class TemplateExportRepositoryImpl extends BaseRepository implements TemplateExportRepository {
    public function getModel() {
        return DM_TEMPLATE_EXPORT::class;
    }

}
