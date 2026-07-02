<?php

namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;
use Core\Service\BaseService;
use Modules\System\Object\FilterDonvi;
use Modules\System\Service\DonviService;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Object\FilterHDXBNXBGDVN;
use Modules\Topic\Repository\HDXBNXBGDVNRepository;
use Modules\Topic\Service\HDXBNXBGDVNService;

class HDXBNXBGDVNServiceImpl extends BaseService implements HDXBNXBGDVNService
{
    /** @var HDXBNXBGDVNRepository */
    protected $baseRepo;

    public function __construct(HDXBNXBGDVNRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterHDXBNXBGDVN $filter, string $page = 'page-1'): array {
        $conditions = $filter->buildConditions();
        // dd($conditions);
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 10,
            "page" => $page,
        ]);
        $result = $this->pagination($paginate);

        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info,
        ];
    }

    public function getList(FilterHDXBNXBGDVN $filter) {
        $list = $this->baseRepo->findAllWithFilter($filter);
        return $list;
    }


    private function getDonviMap(): array {
        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);
        $listDonvi = $donviService->getAllDonvi(new FilterDonvi([
            "IsDeleted" => false,
        ]));

        $map = [];
        foreach ($listDonvi as $donvi) {
            $map[(int) $donvi->id] = (string) ($donvi->TenDonVi ?? "");
        }

        return $map;
    }
}
