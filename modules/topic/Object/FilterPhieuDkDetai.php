<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 * @property ?bool $IsDeleted
 * @property ?string $MaSo
 * @property ?string $TenDeTai
 * @property ?string $TacGia
 * @property ?string $NamXuatBan
 * @property ?string $BienTapVien
 * @property ?int $ID_MangSach
 * @property ?int $HTXB
 * @property ?int $ID_DonVi
 * @property ?int $TrangThai
 * @property ?array $NgayDK
 */
class FilterPhieuDkDetai extends BaseObject {
    public ?bool $IsDeleted = null;
    public ?string $MaSo = null;
    public ?string $TenDeTai = null;
    public ?string $TacGia = null;
    public ?string $NamXuatBan = null;
    public ?string $BienTapVien = null;
    public ?int $ID_MangSach = null;
    public ?int $HTXB = null;
    public ?int $ID_DonVi = null;
    public ?int $TrangThai = null;
    public ?array $NgayDK = null;


    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];
        $conditionsOr = [];

        if(isset($this->IsDeleted)) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }

        if(isset($this->MaSo) && $this->MaSo != "") {
            $conditionsOr[] = ["MaSo" => ['$regex' => new Regex(preg_quote($this->MaSo, "/"), "ui")]];
        }

        if(isset($this->TenDeTai) && $this->TenDeTai != "") {
            $conditionsOr[] = ["TenDeTai" => ['$regex' => new Regex(preg_quote($this->TenDeTai, "/"), "ui")]];
        }

        if(isset($this->TacGia) && $this->TacGia != "") {
            $conditionsOr[] = ["TacGia" => ['$regex' => new Regex(preg_quote($this->TacGia, "/"), "ui")]];
        }

        if(isset($this->BienTapVien) && $this->BienTapVien != "") {
            $conditionsOr[] = ["BienTapVien" => ['$regex' => new Regex(preg_quote($this->BienTapVien, "/"), "ui")]];
        }

        if(isset($this->NamXuatBan) && $this->NamXuatBan != "") {
            $conditions["NamXuatBan"] = (string)$this->NamXuatBan;
        }


        if(isset($this->ID_MangSach) && $this->ID_MangSach != 0) {
            $conditions["ID_MangSach"] = (int)$this->ID_MangSach;
        }

        if(in_array($this->HTXB, [0, 1])) {
            $conditions["HTXB"] = (bool)$this->HTXB;
        }

        if(isset($this->ID_DonVi) && $this->ID_DonVi != 0) {
            $conditions["ID_DonVi"] = (int)$this->ID_DonVi;
        }

        if($this->TrangThai != -1) {
            $conditions["TrangThai"] = (int)$this->TrangThai;
        }

        if (is_array($this->NgayDK) && count($this->NgayDK) >= 2) {
            $fromRaw = $this->NgayDK[0] ?? null;
            $toRaw = $this->NgayDK[1] ?? null;

            if ($fromRaw !== null && $toRaw !== null) {
                $from = $fromRaw instanceof \DateTimeImmutable
                    ? $fromRaw->setTime(0, 0, 0)
                    : new \DateTime(($fromRaw instanceof \DateTimeInterface ? $fromRaw->format("c") : (string) $fromRaw));
                $from->setTime(0, 0, 0);

                $to = $toRaw instanceof \DateTimeImmutable
                    ? $toRaw->setTime(23, 59, 59)
                    : new \DateTime(($toRaw instanceof \DateTimeInterface ? $toRaw->format("c") : (string) $toRaw));
                $to->setTime(23, 59, 59);

                $conditions["NgayDK"] = [
                    '$gte' => $from,
                    '$lte' => $to,
                ];
            }
        }
        if( is_array($conditionsOr) && count($conditionsOr) > 0) {
            $conditions['$or'] = $conditionsOr;
        }
        return $conditions;
    }
}
