<?php
namespace Modules\QualityAssessment\Object;

use Core\Object\BaseObject;
use DateTime;
use MongoDB\BSON\Regex;

/**
 * Filter DSDocRaSoat
 * @property ?string $Title
 * @property ?string $Type
 * @property ?bool $IsSach
 * @property ?bool $Deleted
 * @property ?DateTime $TuNgay
 * @property ?DateTime $DenNgay
 */
class FilterDSDocRaSoat extends BaseObject {

    public ?string $Title = null;
    public ?string $Type = null;
    public ?bool $IsSach = null;
    public ?bool $Deleted = null;
    public ?DateTime $TuNgay = null;
    public ?DateTime $DenNgay = null;

    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions() {
        $conditions = [];

        if ($this->Deleted !== null) {
            $conditions["Deleted"] = (bool) $this->Deleted;
        }

        if ($this->Type !== null && $this->Type !== "") {
            $conditions["Type"] = $this->Type;
        }

        if ($this->IsSach !== null) {
            $conditions["IsSach"] = (bool) $this->IsSach;
        }

        if ($this->Title !== null && $this->Title !== "") {
            $conditions["Title"] = ['$regex' => new Regex(preg_quote($this->Title, "/"), "ui")];
        }

        if ($this->TuNgay !== null && $this->DenNgay !== null) {
            $from = clone $this->TuNgay;
            $to = clone $this->DenNgay;
            $from->setTime(0, 0, 0);
            $to->setTime(23, 59, 59);
            $conditions["CreatedOn"] = ['$gte' => $from, '$lte' => $to];
        }

        return $conditions;
    }
}
