<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\LoaiSnvService;
use Modules\System\Repository\LoaiSnvRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class LoaiSnvServiceImpl extends BaseService implements LoaiSnvService
{
    /** @var LoaiSnvRepository */
    protected $baseRepo;

    public function __construct(LoaiSnvRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}