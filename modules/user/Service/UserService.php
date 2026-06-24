<?php
namespace Modules\User\Service;

use Core\Service\IBaseService;
use Modules\User\Model\User;
use Modules\User\Object\FilterUser;

interface UserService extends IBaseService {
    /** api login vào hệ thống */
    public function login(string $username, string $password): ?User;
    /** api logout khỏi hệ thống */
    public function logOut(): void;
    /** api khôi phục mật khẩu */
    public function forgetPassword(string $email): ?User;
    /** api lấy danh sách người dùng
    * @return array<User>
    */
    public function getPaginateUser(FilterUser $filter, string $page = 'page-1'): array;
    /** api lấy danh sách người dùng
    * @return array<User>
    */
    public function getListUser(FilterUser $filter);
    /** api thêm mới hoặc cập nhật người dùng
    * @param User $user
    * @return User
    */
    public function storeUser(array $data): User;
    /** api xóa người dùng
    * @param int $id
    * @return void
    */
    public function deleteUser(int $id): bool;

    /** api chuyển đổi dữ liệu người dùng từ hệ thống cũ sang hệ thống mới
    * @return void
    */
    public function convertDataCanBoQuyen(): void;

    /** api tạo tài khoản
    * @param array $data
    * @param int $id
    * @return User
    */
    public function createAccount(array $data, int $id): User;

    /** api reset mật khẩu
    * @param int $id
    * @return boolean
    */
    public function resetPassword(int $id): bool;

    /** api lấy số lượng cán bộ trong nhóm
    * @param $listNhom
    * @return array
    */
    public function getCountCanboInNhom($listNhom): array;

    /** api lấy danh sách biên tập viên
    * @param int $id
    * @return Donvi
    */
    public function getListBTV() ;
}
