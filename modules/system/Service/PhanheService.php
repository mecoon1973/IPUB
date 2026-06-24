<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_PHANHE;

/**
 * @extends IBaseService<DM_PHANHE>
 */
interface PhanheService extends IBaseService {

    public function getAllPhanhe();
}
