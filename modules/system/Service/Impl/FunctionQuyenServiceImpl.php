<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\FunctionQuyenService;
use Modules\System\Repository\FunctionQuyenRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class FunctionQuyenServiceImpl extends BaseService implements FunctionQuyenService
{
    /** @var FunctionQuyenRepository */
    protected $baseRepo;

    public function __construct(FunctionQuyenRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}