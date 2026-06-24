<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\PhanheService;
use Modules\System\Repository\PhanheRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class PhanheServiceImpl extends BaseService implements PhanheService
{
    /** @var PhanheRepository */
    protected $baseRepo;

    public function __construct(PhanheRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getAllPhanhe(){
        return $this->baseRepo->findAll();
    }

}
