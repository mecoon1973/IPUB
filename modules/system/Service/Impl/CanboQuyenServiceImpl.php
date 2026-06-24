<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\CanboQuyenService;
use Modules\System\Repository\CanboQuyenRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class CanboQuyenServiceImpl extends BaseService implements CanboQuyenService
{
    /** @var CanboQuyenRepository */
    protected $baseRepo;

    public function __construct(CanboQuyenRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}