<?php
namespace Modules\Topic\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\Topic\Service\CT_PhieuDkDetai_BtvService;
use Modules\Topic\Repository\CT_PhieuDkDetai_BtvRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class CT_PhieuDkDetai_BtvServiceImpl extends BaseService implements CT_PhieuDkDetai_BtvService
{
    /** @var CT_PhieuDkDetai_BtvRepository */
    protected $baseRepo;

    public function __construct(CT_PhieuDkDetai_BtvRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}