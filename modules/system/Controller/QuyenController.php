<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\System\Object\FilterChucnang;
use Modules\System\Request\FrmSearchQuyenRequest;
use Modules\System\Request\FrmStoreQuyenRequest;
use Modules\System\Service\ChucnangService;
use Modules\System\Service\QuyenService;

class QuyenController extends Controller {

    public function viewManageQuyen(Request $request): View {
        return view('system::viewManageQuyen');
    }

    public function viewPermissionSettings(Request $request, ?int $id = null): View {
        /** @var QuyenService $quyenService */
        $quyenService = app(QuyenService::class);
        $quyen = $quyenService->findOne("no-cache",['id' => $id]);
        if(!$quyen){
            abort(404, 'Quyền không tồn tại');
        }
        /** @var ChucnangService $chucnangService */
        $chucnangService = app(ChucnangService::class);
        $listChucnang = $chucnangService->getAllChucnang(new FilterChucnang([
            'Deleted' => false,
        ]));
        return view('system::viewPermissionSettings', [
            'quyen' => $quyen,
            'listChucnang' => $listChucnang,
        ]);
    }

    public function viewStoreQuyen(Request $request, ?int $id = null): View {
        /** @var QuyenService $quyenService */
        $quyenService = app(QuyenService::class);
        $parentId = $request->query('parentId', 0);
        if($parentId) {
            $parentId = (int) $parentId;
        }
        $quyen = $id ? $quyenService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreQuyen', [
            'quyen' => $quyen,
            'parentId' => $parentId,
        ]);
    }

    public function getAllQuyen(FrmSearchQuyenRequest $request): JsonResponse {
        /** @var QuyenService $quyenService */
        $quyenService = app(QuyenService::class);
        try {
            $filter = $request->toFilter();
            $result = $quyenService->getAllQuyen($filter);
            return response()->json($result, 200);
        }
        catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreQuyenRequest $request): JsonResponse {
        /** @var QuyenService $quyenService */
        $quyenService = app(QuyenService::class);
        try {
            $payload = $request->toPayload();
            $quyen = $quyenService->store($payload);
            return response()->json($quyen, 200);
        }
        catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var QuyenService $quyenService */
        $quyenService = app(QuyenService::class);
        try {
            $result = $quyenService->delete($id);
            return response()->json($result, 200);
        }
        catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
