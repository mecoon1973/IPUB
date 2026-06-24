<?php
namespace Modules\LegalDeposit\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\LegalDeposit\Service\CT_ToKhaiLuuChuyenService;
use Modules\LegalDeposit\Repository\CT_ToKhaiLuuChuyenRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class CT_ToKhaiLuuChuyenServiceImpl extends BaseService implements CT_ToKhaiLuuChuyenService
{
    /** @var CT_ToKhaiLuuChuyenRepository */
    protected $baseRepo;

    public function __construct(CT_ToKhaiLuuChuyenRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}