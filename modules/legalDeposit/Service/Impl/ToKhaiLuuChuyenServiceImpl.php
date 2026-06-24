<?php
namespace Modules\LegalDeposit\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\LegalDeposit\Service\ToKhaiLuuChuyenService;
use Modules\LegalDeposit\Repository\ToKhaiLuuChuyenRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class ToKhaiLuuChuyenServiceImpl extends BaseService implements ToKhaiLuuChuyenService
{
    /** @var ToKhaiLuuChuyenRepository */
    protected $baseRepo;

    public function __construct(ToKhaiLuuChuyenRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}