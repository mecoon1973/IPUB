<?php

namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\PHIEU_DK_KHXB_CXB;
use Modules\Topic\Object\FilterPhieuDkKhxbCxb;

/**
 * @extends IBaseService<PHIEU_DK_KHXB_CXB>
 */
interface PhieuDkKhxbCxbService extends IBaseService
{
    public function getPaginate(FilterPhieuDkKhxbCxb $filter, string $page = 'page-1'): array;

    public function getList(FilterPhieuDkKhxbCxb $filter);

    public function previewMaSo(): string;

    public function getDetail(int $id): array;

    public function store(array $data, int $idCanBo): array;

    public function previewMaSoCxbSeq(): int;

    public function capMaSoCxb(int $idPhieu, array $data, int $idCanBo): array;

    public function capMaIsbn(int $idPhieu, array $isbnList, int $idCanBo): array;

    public function ketChuyenThanhSach(int $idPhieu, array $listIdDeTai, int $idCanBo): array;

    public function getXetDuyet(int $idPhieu): array;

    public function luuXetDuyet(int $idPhieu, array $items, int $idCanBo): array;
}
