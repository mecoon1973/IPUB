<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\CT_DvLoaiSNVService;
use Modules\System\Repository\CT_DvLoaiSNVRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class CT_DvLoaiSNVServiceImpl extends BaseService implements CT_DvLoaiSNVService
{
    /** @var CT_DvLoaiSNVRepository */
    protected $baseRepo;

    public function __construct(CT_DvLoaiSNVRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}