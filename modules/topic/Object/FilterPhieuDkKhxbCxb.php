<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;
use DateTime;
use MongoDB\BSON\Regex;

/**
 * @property ?string $TuKhoa
 * @property ?DateTime $startDate
 * @property ?DateTime $endDate
 * @property ?bool $IsDeleted
 */
class FilterPhieuDkKhxbCxb extends BaseObject
{
    public ?string $TuKhoa = null;
    public ?DateTime $startDate = null;
    public ?DateTime $endDate = null;
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

        if ($this->TuKhoa !== null && $this->TuKhoa !== '') {
            $regex = new Regex(preg_quote($this->TuKhoa, '/'), 'ui');
            $conditionsOr[] = ['MaSo' => ['$regex' => $regex]];
            $conditionsOr[] = ['TieuDe' => ['$regex' => $regex]];
            $conditionsOr[] = ['NoiDung' => ['$regex' => $regex]];
            $conditionsOr[] = ['SoGiayPhep' => ['$regex' => $regex]];
        }

        if ($this->startDate !== null && $this->endDate !== null) {
            $from = clone $this->startDate;
            $to = clone $this->endDate;
            $from->setTime(0, 0, 0);
            $to->setTime(23, 59, 59);
            $conditions['NgayDK'] = ['$gte' => $from, '$lte' => $to];
        }

        if (count($conditionsOr) > 0) {
            $conditions['$or'] = $conditionsOr;
        }

        return $conditions;
    }
}
