<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\CT_DonviLC_LoaiXBPLCService;
use Modules\System\Repository\CT_DonviLC_LoaiXBPLCRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class CT_DonviLC_LoaiXBPLCServiceImpl extends BaseService implements CT_DonviLC_LoaiXBPLCService
{
    /** @var CT_DonviLC_LoaiXBPLCRepository */
    protected $baseRepo;

    public function __construct(CT_DonviLC_LoaiXBPLCRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}