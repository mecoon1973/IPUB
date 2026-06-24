<?php
namespace Modules\User\Service\Impl;

use Core\Object\Paginate;
use Core\Service\BaseService;
use Exception;
use Modules\User\Model\User;
use Modules\User\Repository\UserRepository;
use Modules\User\Service\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Core\Service\BaseConvertTool;
use Modules\System\Model\DM_NHOM;
use Modules\System\Service\CanboQuyenService;
use Modules\User\Object\FilterUser;

class UserServiceImpl extends BaseService implements UserService
{
    use BaseConvertTool;
    public function __construct(UserRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function login(string $username, string $password): ?User{
        $user = $this->baseRepo->findOne(["UserName" => $username]);
        if (!$user) {
            throw new Exception('Tên đăng nhập không tồn tại');
        }
        $hashedPassword = md5($password);

        if (!hash_equals((string) $user->PassWord, $hashedPassword)) {
            throw new Exception('Mật khẩu không đúng');
        }
        unset($user->PassWord);
        if ($user instanceof User) {
            Auth::login($user);
        }
        return $user;
    }

    public function logOut(): void{
        Auth::logout();
    }

    public function forgetPassword(string $email): ?User{
        $user = $this->baseRepo->findOne(["Email" => $email]);
        if (!$user) {
            throw new Exception('Email không tồn tại');
        }
        return $user;
    }

    public function getPaginateUser(FilterUser $filter, string $page = 'page-1'): array{
        $conditions = $filter->buildConditions();
        if(array_key_exists("IsDeleted", $conditions)) {
            $conditions["IsDeleted"] = (bool)$conditions["IsDeleted"];
        }
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 10,
            "page" => $page,
            "loadRelations" => $filter->relations
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getListUser(FilterUser $filter){
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }


    public function getListBTV(){
        $result = $this->baseRepo->findAllWithFilter(new FilterUser([
            "IsDeleted" => false,
            "IsEditor" => true,
            "relations" => ["donvi", "chuyenmon"],
        ]));
        return $result;
    }

    public function storeUser(array $data): User {
        if (data_get($data, '_id', 0) != 0) {

            /** @var User $user */
            $user = $this->baseRepo->get($data['_id']);
            if ($user) {
                unset($data['_id']);
                $user->update($data);

                return $user;
            }
        }

        if (($data['MaCanBo'] ?? '') !== '') {
            $this->isExistUser("MaCanBo", $data['MaCanBo']);
        }


        /** @var User $user */
        $user = $this->baseRepo->create($data);

        if (! $user) {
            throw new Exception('Có lỗi xảy ra, vui lòng thử lại');
        }

        return $user;
    }

    public function deleteUser(int $id): bool {
        $user = $this->baseRepo->get($id);
        if(!$user){
            throw new Exception("Người dùng không tồn tại");
        }
        $user->IsDeleted = true;
        return $user->save();
    }

    private function isExistUser(string $type, string $value) {
        $user = $this->baseRepo->findOne([$type => $value]);
        if($user){
            throw new Exception("{$type} đã tồn tại");
        }
    }

    public function convertDataCanBoQuyen(): void {
        dump("convertDataCanBoQuyen: START");
        $success = 0;
        $failed = 0;
        $this->baseConvert("convertDataCanBoQuyen", $this->baseRepo, [], function($user) use (&$success, &$failed) {
            /** @var CanboQuyenService $canBoQuyenService */
            $canBoQuyenService = app(CanBoQuyenService::class);
            $listCanboQuyen = $canBoQuyenService->findAll(["ID_CanBo" => $user->_id, "InUsed" => true, "IsDeleted" => false]);
            $listIdQuyen = [];
            foreach($listCanboQuyen as $canboQuyen){
                $listIdQuyen[] = $canboQuyen->ID_QUYEN;
            }
            $user->quyen_ids = $listIdQuyen;
            $result = $user->save();
            if($result){
                $success++;
            } else {
                $failed++;
            }
        });
        dump("convertDataCanBoQuyen: FINISH");
        dump("Success: {$success}");
        dump("Failed: {$failed}");
        dump("Total: " . ($success + $failed));
    }

    public function createAccount(array $data, int $id): User {
        $user = $this->baseRepo->get($id);
        if(!$user){
            throw new Exception("Người dùng không tồn tại");
        }
        if($data['PassWord'] !== $data['ConfirmPassWord']){
            throw new Exception("Mật khẩu và nhập lại mật khẩu không khớp");
        }

        $this->isExistUser("UserName", $data['UserName']);
        $user->UserName = $data['UserName'];
        $user->PassWord = md5($data['PassWord']);
        // chưa test kỹ xem nó là fill gì
        // $user->ID_DonVi = $data['ID_DonVi'];
        $user->save();
        return $user->refresh();
    }

    public function resetPassword(int $id): bool {
        $user = $this->baseRepo->get($id);
        if(!$user){
            throw new Exception("Người dùng không tồn tại");
        }
        $user->PassWord = md5("123456");
        return $user->save();
    }

    public function getCountCanboInNhom($listNhom): array {
        if ($listNhom == []) {
            return [];
        }
        $nhomIds = [];
        foreach ($listNhom as $item) {
            $id = $item instanceof DM_NHOM ? (int) $item->_id : (int) $item;
            if ($id > 0) {
                $nhomIds[$id] = $id;
            }
        }
        if ($nhomIds === []) {
            return [];
        }
        $idList = array_values($nhomIds);
        $counts = array_fill_keys($idList, 0);
        $pipeline = [
            ['$match' => [
                'IsDeleted' => false,
                'nhom_ids' => ['$in' => $idList],
            ]],
            ['$unwind' => '$nhom_ids'],
            ['$match' => ['nhom_ids' => ['$in' => $idList]]],
            ['$group' => [
                '_id' => '$nhom_ids',
                'count' => ['$sum' => 1],
            ]],
        ];

        $cursor = $this->baseRepo->aggregate($pipeline);

        foreach ($cursor as $row) {
            $id = isset($row['_id']) ? (int) $row['_id'] : null;
            if ($id !== null) {
                $counts[$id] = (int) ($row['count'] ?? 0);
            }
        }
        return $counts;
    }


}
