<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;
use DateTime;
use Modules\User\Object\FilterUser;
use Modules\User\Service\UserService;
use MongoDB\BSON\Regex;

/**
 * Đối tượng filter cho hệ thống log
 * @property DateTime|null $startDate Ngày bắt đầu
 * @property DateTime|null $endDate Ngày kết thúc
 * @property string|null $accountName Tài khoản đăng nhập
 * @property string|null $userName Họ tên
 * @property int|null $id_Dv ID đơn vị
 */
class FilterSystemLog extends BaseObject {

    public ?DateTime $startDate = null;
    public ?DateTime $endDate = null;
    public ?string $accountName = null;
    public ?string $userName = null;
    public ?int $id_Dv = null;

    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions() {
        $conditions = [];

        if($this->startDate !== null && $this->endDate !== null) {
            $from = clone $this->startDate;
            $to = clone $this->endDate;
            $from->setTime(0, 0, 0);
            $to->setTime(23, 59, 59);
            $conditions["ActionTime"] = ['$gte' => $from, '$lte' => $to];
        }

        if($this->id_Dv !== null && $this->id_Dv > 0) {
            $conditions["id_Dv"] = $this->id_Dv;
        }

        /**
         * tim user theo tài khoản đăng nhập hoặc họ tên
         * */
        $idUsers = [];
        if(($this->accountName !== null && $this->accountName !== "") || ($this->userName !== null && $this->userName !== "")) {
            $conditionsUser = ['$or' => []];

            if($this->accountName !== null) {
                $conditionsUser['$or'][] = ["UserName" => ['$regex' => new Regex(preg_quote($this->accountName, "/"), "ui")]];
            }
            if($this->userName !== null) {
                $conditionsUser['$or'][] = ["HoTen" => ['$regex' => new Regex(preg_quote($this->userName, "/"), "ui")]];
            }
            /** @var UserService $userService */
            $userService = app(UserService::class);
            $listUser = $userService->findAll($conditionsUser, [], ["_id"]);
            foreach($listUser as $user) {
                $idUsers[] = $user->_id;
            }
        }

        if(count($idUsers) > 0) {
            $conditions["UserID"] = ['$in' => $idUsers];
        }
        //
        return $conditions;
    }
}
