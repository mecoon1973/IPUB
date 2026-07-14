<?php
namespace Modules\Topic\Object;

use Core\Object\BaseObject;
use DateTime;
use MongoDB\BSON\Regex;

/**
 * @property ?string $TuKhoa
 * @property ?DateTime $startDate
 * @property ?DateTime $endDate
 * @property ?int $ID_DV
 * @property ?bool $IsDeleted
 */
class FilterPhieuChuyenBanThao extends BaseObject
{
    public ?string $TuKhoa = null;
    public ?DateTime $startDate = null;
    public ?DateTime $endDate = null;
    public ?int $ID_DV = null;
    public ?bool $IsDeleted = null;

    public function __construct($input = [])
    {
        parent::__construct($input);
    }

    public function buildConditions(): array
    {
        $conditions = [];
        $conditionsOr = [];

        if ($this->IsDeleted !== null) {
            $conditions['IsDeleted'] = (bool) $this->IsDeleted;
        } else {
            $conditions['IsDeleted'] = ['$ne' => true];
        }

        if ($this->ID_DV !== null && (int) $this->ID_DV > 0) {
            $conditions['ID_DV'] = (int) $this->ID_DV;
        }

        if ($this->TuKhoa !== null && $this->TuKhoa !== '') {
            $regex = new Regex(preg_quote($this->TuKhoa, '/'), 'ui');
            $conditionsOr[] = ['NguoiGiao' => ['$regex' => $regex]];
            $conditionsOr[] = ['NguoiNhan' => ['$regex' => $regex]];
            $conditionsOr[] = ['TacGia' => ['$regex' => $regex]];
            $conditionsOr[] = ['BienTapVien' => ['$regex' => $regex]];
            $conditionsOr[] = ['GhiChu' => ['$regex' => $regex]];
        }

        if ($this->startDate !== null && $this->endDate !== null) {
            $from = clone $this->startDate;
            $to = clone $this->endDate;
            $from->setTime(0, 0, 0);
            $to->setTime(23, 59, 59);
            $conditions['NgayGiao'] = ['$gte' => $from, '$lte' => $to];
        }

        if (count($conditionsOr) > 0) {
            $conditions['$or'] = $conditionsOr;
        }

        return $conditions;
    }
}
