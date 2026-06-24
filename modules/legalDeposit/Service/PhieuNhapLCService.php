<?php
namespace Modules\legalDeposit\Service;

use Core\Service\IBaseService;
use Modules\legalDeposit\Model\DM_PhieuNhapLC;
use Modules\legalDeposit\Object\FilterPhieuNhapLC;

/**
 * @extends IBaseService<DM_PhieuNhapLC>
 */
interface PhieuNhapLCService extends IBaseService {
    public function getPaginate(FilterPhieuNhapLC $filter, string $page = 'page-1') : array;
    public function getList(FilterPhieuNhapLC $filter);
    public function store(array $data) : DM_PhieuNhapLC;
    public function delete(int $id) : bool;
}
