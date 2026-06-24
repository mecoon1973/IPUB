<?php

namespace Modules\User\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\User\Model\User;
use Modules\Page\Request\FrmForgetPasswordRequest;
use Modules\Page\Request\FrmLoginRequest;
use Modules\System\Traits\TraitsGetData;
use Modules\User\Request\FrmCreateAccountRequest;
use Modules\User\Request\FrmSearchUserRequest;
use Modules\User\Request\FrmStoreUserRequest;
use Modules\User\Service\UserService;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller {

    use TraitsGetData;
    public function viewLogin(): View {
        return view('page::login');
    }

    public function viewForgetPassword(): View {
        return view('page::forgetPassword');
    }

    public function viewManageUser(): View {
        return view('user::viewManageUser');
    }

    public function viewCreateAccount(int $id): View {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $user = $userService->findOne("no-cache", ['_id' => $id]);
        if(!$user){
            abort(404, 'Người dùng không tồn tại');
        }
        return view('user::viewCreateAccount', [
            'user' => $user,
        ]);
    }

    public function viewAssignPermissions(int $id): View {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $user = $userService->findOne("no-cache", ['_id' => $id]);
        if (!$user) {
            abort(404, 'Người dùng không tồn tại');
        }
        $user->load(['chucvu', 'donvi']);
        $dataView = $this->getDataView(["listNhom", "listQuyen"]);
        $dataView['user'] = $user;
        return view('user::viewAssignPermissions', $dataView);
    }

    public function viewStoreUser(Request $request, ?int $id = null): View {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $user = $id ? $userService->findOne("no-cache", ['id' => $id]) : null;
        return view('user::viewStoreUser', [
            'user' => $user,
        ]);
    }

    public function login(FrmLoginRequest $request): JsonResponse {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $data = $request->safe();
        try {
            $user = $userService->login(
                (string) $data['username'],
                (string) $data['password']
            );

            return response()->json([
                'message' => 'Đăng nhập thành công',
                'user' => $user,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 401);
        }
    }

    public function forgetPassword(FrmForgetPasswordRequest $request): JsonResponse {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $data = $request->safe();
        $user = $userService->forgetPassword(
            (string) $data['email']
        );
        return response()->json([
            'message' => 'Email khôi phục mật khẩu đã được gửi',
        ], 200);
    }

    public function getPaginateUser(FrmSearchUserRequest $request, string $page = 'page-1'): JsonResponse {
        $filter = $request->toFilter();
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $result = $userService->getPaginateUser($filter, $page);
        return response()->json($result, 200);
    }
    public function getListUser(FrmSearchUserRequest $request): JsonResponse {
        $filter = $request->toFilter();
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $result = $userService->getListUser($filter);
        return response()->json($result, 200);
    }

    public function storeUser(FrmStoreUserRequest $request): JsonResponse {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $data = $request->toPayload();
        $user = $userService->storeUser($data);
        return response()->json($user, 200);
    }

    public function deleteUser(Request $request, int $id): JsonResponse {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $user = $userService->deleteUser($id);
        return response()->json($user, 200);
    }

    public function createAccount(FrmCreateAccountRequest $request, int $id): JsonResponse {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $data = $request->toPayload();
        $user = $userService->createAccount($data, $id);
        return response()->json($user, 200);
    }

    public function resetPassword(Request $request, int $id): JsonResponse {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $result = $userService->resetPassword($id);
        return response()->json($result, 200);
    }
}
