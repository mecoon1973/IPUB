<?php
namespace Modules\Book\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\Book\Service\SachService;
use Modules\Book\Repository\SachRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class SachServiceImpl extends BaseService implements SachService
{
    /** @var SachRepository */
    protected $baseRepo;

    public function __construct(SachRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

}