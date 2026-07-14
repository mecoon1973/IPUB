<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 * @property ?string $TenDeTai
 * @property ?int $ID_DonVi
 * @property ?int $PhanCong
 * @property ?int $TrangThai
 */
class FilterHDXBNXBGDVN extends BaseObject {
    /** Trạng thái thuộc luồng HĐXB NXBGDVN */
    public const TRANG_THAI_HDXB_NXBGDVN = [
        PhieuDkDetaiTrangThai::HDXB_NXBGDVN_TRA_LAI,
        PhieuDkDetaiTrangThai::HDXB_NXBGDVN_DANG_XET,
        PhieuDkDetaiTrangThai::HDXB_NXBGDVN_PHE_DUYET,
        PhieuDkDetaiTrangThai::HDXB_NXBGDVN_CHUA_XET,
    ];

    public const PHAN_CONG_TAT_CA = -1;
    public const PHAN_CONG_CHUA = 0;
    public const PHAN_CONG_DA_TAT_CA = 1;
    public const PHAN_CONG_DA_CA_NHAN = 2;

    public ?string $TenDeTai = null;
    public ?int $ID_DonVi = null;
    public ?int $PhanCong = null;
    public ?int $TrangThai = null;
    public ?int $IsDeleted = null;

    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions(): array {
        $and = [];

        $base = [];

        if ($this->IsDeleted !== null) {
            $base["IsDeleted"] = (bool) $this->IsDeleted;
        }

        if ($this->TrangThai !== null && $this->TrangThai !== -1) {
            $base["TrangThai"] = (int) $this->TrangThai;
        } else {
            $base["TrangThai"] = ['$in' => self::TRANG_THAI_HDXB_NXBGDVN];
        }

        $and[] = $base;

        if (isset($this->ID_DonVi) && $this->ID_DonVi !== 0) {
            $and[] = ["ID_DonVi" => (int) $this->ID_DonVi];
        }

        $keywordOr = $this->buildTenDeTaiConditions();
        if (count($keywordOr) > 0) {
            $and[] = ['$or' => $keywordOr];
        }

        if (count($and) === 1) {
            return $and[0];
        }

        return ['$and' => $and];
    }

    private function buildTenDeTaiConditions(): array {
        if (!isset($this->TenDeTai) || trim($this->TenDeTai) === "") {
            return [];
        }

        $keywords = array_filter(array_map('trim', explode(';', $this->TenDeTai)));
        $conditionsOr = [];

        foreach ($keywords as $keyword) {
            if ($keyword === "") {
                continue;
            }
            $conditionsOr[] = [
                "TenDeTai" => ['$regex' => new Regex(preg_quote($keyword, "/"), "ui")],
            ];
        }

        return $conditionsOr;
    }
}
