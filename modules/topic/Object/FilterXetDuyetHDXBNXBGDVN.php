<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;

/**
 * @property ?string $TuNgay
 * @property ?string $DenNgay
 * @property ?int $ID_DonVi
 * @property int[] $idsDeTai
 */
class FilterXetDuyetHDXBNXBGDVN extends BaseObject
{
    public ?string $TuNgay = null;
    public ?string $DenNgay = null;
    public ?int $ID_DonVi = null;
    /** @var int[] */
    public array $idsDeTai = [];

    public function __construct($input = [])
    {
        parent::__construct($input);
    }

    /** Điều kiện phân công đọc duyệt đang hiệu lực trên ipub_nx_canbo_detai */
    public function buildNxPhanCongConditions(): array
    {
        $conditions = [
            'LaPhanCong' => true,
            'IsDeleted' => false,
            'InUsed' => true,
        ];

        if (count($this->idsDeTai) > 0) {
            $conditions['ID_DeTai'] = ['$in' => array_values(array_map('intval', $this->idsDeTai))];
        }

        $ngayNx = $this->buildNgayNxConditions();
        if ($ngayNx !== null) {
            $conditions['NgayNX'] = $ngayNx;
        }

        return $conditions;
    }

    /**
     * Điều kiện phiếu đề tài đang HĐXB xét duyệt.
     *
     * @param int[] $idsDeTai
     */
    public function buildPhieuConditions(array $idsDeTai): array
    {
        $idsDeTai = array_values(array_filter(array_map('intval', $idsDeTai)));
        if (count($idsDeTai) === 0) {
            return ['_id' => 0];
        }

        $conditions = [
            '_id' => ['$in' => $idsDeTai],
            'TrangThai' => ['$in' => [PhieuDkDetaiTrangThai::HDXB_NXBGDVN_DANG_XET, PhieuDkDetaiTrangThai::HDXB_NXBGDVN_PHE_DUYET]],
            'IsDeleted' => false,
        ];

        if ($this->ID_DonVi !== null && $this->ID_DonVi > 0) {
            $conditions['ID_DonVi'] = (int) $this->ID_DonVi;
        }

        return $conditions;
    }

    public function hasNgayNxFilter(): bool
    {
        return ($this->TuNgay !== null && $this->TuNgay !== '')
            || ($this->DenNgay !== null && $this->DenNgay !== '');
    }

    /** @return ?array<string, \DateTime> */
    public function buildNgayNxConditions(): ?array
    {
        if (!$this->hasNgayNxFilter()) {
            return null;
        }

        $range = [];

        if ($this->TuNgay !== null && $this->TuNgay !== '') {
            $from = new \DateTime($this->TuNgay);
            $from->setTime(0, 0, 0);
            $range['$gte'] = $from;
        }

        if ($this->DenNgay !== null && $this->DenNgay !== '') {
            $to = new \DateTime($this->DenNgay);
            $to->setTime(23, 59, 59);
            $range['$lte'] = $to;
        }

        return count($range) > 0 ? $range : null;
    }
}
