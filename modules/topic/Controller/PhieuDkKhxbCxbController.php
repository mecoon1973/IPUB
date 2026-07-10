<?php

namespace Modules\Topic\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\System\Traits\TraitsGetData;
use Modules\Topic\Request\FrmCapMaIsbnRequest;
use Modules\Topic\Request\FrmCapMaSoCxbRequest;
use Modules\Topic\Request\FrmKetChuyenThanhSachRequest;
use Modules\Topic\Request\FrmLuuXetDuyetPhieuDkKhxbCxbRequest;
use Modules\Topic\Request\FrmSearchPhieuDkKhxbCxbRequest;
use Modules\Topic\Request\FrmStorePhieuDkKhxbCxbRequest;
use Modules\Topic\Service\PhieuDkKhxbCxbService;
use Modules\User\Model\User;
use Modules\User\Service\UserService;

class PhieuDkKhxbCxbController extends Controller
{
    use TraitsGetData;

    public function viewManagePhieuDkKhxbCxb(Request $request): View
    {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $dataView = $this->getDataView(['mapTrangThai']);
        $dataView['listUsers'] = $userService->getListBTV();

        return view('topic::viewManagePhieuDkKhxbCxb', $dataView);
    }

    public function viewStorePhieuDkKhxbCxb(Request $request, ?int $id = null): View
    {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);

        $dataView = $this->getDataView(['mapTrangThai', 'listDonvi', 'listMangsach']);
        $dataView['listUsers'] = $userService->getListBTV();
        $dataView['phieuDkKhxbCxb'] = null;
        $dataView['listDeTai'] = [];

        if ($id) {
            $detail = $phieuDkKhxbCxbService->getDetail($id);
            $dataView['phieuDkKhxbCxb'] = $detail['phieu'];
            $dataView['listDeTai'] = $detail['listDeTai'];
        }

        return view('topic::viewStorePhieuDkKhxbCxb', $dataView);
    }

    public function viewCapMaIsbnPhieuDkKhxbCxb(Request $request, int $id): View
    {
        /** @var UserService $userService */
        $userService = app(UserService::class);
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);

        $detail = $phieuDkKhxbCxbService->getDetail($id);

        $dataView = [
            'listUsers' => $userService->getListBTV(),
            'phieuDkKhxbCxb' => $detail['phieu'],
            'listDeTai' => $detail['listDeTai'],
        ];

        return view('topic::viewCapMaIsbnPhieuDkKhxbCxb', $dataView);
    }

    public function getPaginate(FrmSearchPhieuDkKhxbCxbRequest $request, string $page = 'page-1'): JsonResponse
    {
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);
        try {
            $filter = $request->toFilter();
            $result = $phieuDkKhxbCxbService->getPaginate($filter, $page);

            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchPhieuDkKhxbCxbRequest $request): JsonResponse
    {
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);
        try {
            $filter = $request->toFilter();
            $result = $phieuDkKhxbCxbService->getList($filter);

            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function previewMaSo(Request $request): JsonResponse
    {
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);
        try {
            return response()->json([
                'MaSo' => $phieuDkKhxbCxbService->previewMaSo(),
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
    /**
     * Lưu phiếu đăng ký CXB.
     */
    public function store(FrmStorePhieuDkKhxbCxbRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);
        try {
            $result = $phieuDkKhxbCxbService->store($request->toPayload(), (int) $user->_id);

            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
    /**
     * Xem trước mã số CXB.
     */
    public function getDetail(Request $request, int $id): JsonResponse
    {
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);
        try {
            $result = $phieuDkKhxbCxbService->getDetail($id);

            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function previewMaSoCxb(Request $request): JsonResponse
    {
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);
        try {
            return response()->json([
                'MaSoCxb' => $phieuDkKhxbCxbService->previewMaSoCxbSeq(),
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
    /**
     * Cấp mã số CXB.
     */
    public function capMaSoCxb(FrmCapMaSoCxbRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);

        $payload = $request->toPayload();
        $result = $phieuDkKhxbCxbService->capMaSoCxb((int) $payload['idPhieu'], $payload, (int) $user->_id);

        return response()->json($result, 200);

    }

    public function capMaIsbn(FrmCapMaIsbnRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);

        $payload = $request->toPayload();
        $result = $phieuDkKhxbCxbService->capMaIsbn(
            (int) $payload['idPhieu'],
            $payload['listIsbn'],
            (int) $user->_id
        );

        return response()->json($result, 200);

    }

    public function ketChuyenThanhSach(FrmKetChuyenThanhSachRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);

        $payload = $request->toPayload();
        $result = $phieuDkKhxbCxbService->ketChuyenThanhSach(
            (int) $payload['idPhieu'],
            $payload['listIdDeTai'],
            (int) $user->_id
        );

        return response()->json($result, 200);

    }

    public function getXetDuyet(Request $request, int $id): JsonResponse
    {
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);
        try {
            $result = $phieuDkKhxbCxbService->getXetDuyet($id);

            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function luuXetDuyet(FrmLuuXetDuyetPhieuDkKhxbCxbRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var PhieuDkKhxbCxbService $phieuDkKhxbCxbService */
        $phieuDkKhxbCxbService = app(PhieuDkKhxbCxbService::class);
        try {
            $payload = $request->toPayload();
            $result = $phieuDkKhxbCxbService->luuXetDuyet(
                (int) $payload['idPhieu'],
                $payload['items'],
                (int) $user->_id
            );

            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
