<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\NhomQuyenService;
use Modules\System\Repository\NhomQuyenRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class NhomQuyenServiceImpl extends BaseService implements NhomQuyenService
{
    /** @var NhomQuyenRepository */
    protected $baseRepo;

    public function __construct(NhomQuyenRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}