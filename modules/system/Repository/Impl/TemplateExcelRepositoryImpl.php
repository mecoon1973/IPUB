<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\TemplateExcelRepository;
use Modules\System\Model\DM_TEMPLATE_EXCEL;


class TemplateExcelRepositoryImpl extends BaseRepository implements TemplateExcelRepository {
    public function getModel() {
        return DM_TEMPLATE_EXCEL::class;
    }

}
