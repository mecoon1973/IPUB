<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\BienMoiTruongDonViService;
use Modules\System\Repository\BienMoiTruongDonViRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class BienMoiTruongDonViServiceImpl extends BaseService implements BienMoiTruongDonViService
{
    /** @var BienMoiTruongDonViRepository */
    protected $baseRepo;

    public function __construct(BienMoiTruongDonViRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}