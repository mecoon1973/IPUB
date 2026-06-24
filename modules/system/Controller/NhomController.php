<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Object\FilterQuyen;
use Modules\System\Request\FrmAddCanboToNhomRequest;
use Modules\System\Request\FrmSearchNhomRequest;
use Modules\System\Request\FrmStoreNhomRequest;
use Modules\System\Service\NhomService;
use Modules\System\Service\QuyenService;

class NhomController extends Controller {

    public function viewManageNhom(Request $request): View {
        return view('system::viewManageNhom');
    }

    public function viewStoreNhom(Request $request, ?int $id = null): View {
        /** @var NhomService $nhomService */
        $nhomService = app(NhomService::class);
        $nhom = $id ? $nhomService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreNhom', [
            'nhom' => $nhom,
        ]);
    }

    public function viewPermissionSettingsNhom(Request $request, int $id): View {
        /** @var NhomService $nhomService */
        $nhomService = app(NhomService::class);
        $nhom = $nhomService->findOne("no-cache",['id' => $id]);

        if(!$nhom){
            abort(404, 'Nhóm không tồn tại');
        }
        /** @var QuyenService $quyenService */
        $quyenService = app(QuyenService::class);
        $listQuyen = $quyenService->getAllQuyen(new FilterQuyen([
            'IsDeleted' => false,
        ]));

        return view('system::viewPermissionSettingsNhom', [
            'nhom' => $nhom,
            'listQuyen' => $listQuyen,
        ]);
    }

    public function viewManageCanboInNhom(Request $request, int $id): View {
        /** @var NhomService $nhomService */
        $nhomService = app(NhomService::class);
        $nhom = $nhomService->findOne("no-cache",['id' => $id]);
        return view('system::viewManageCanboInNhom', [
            'nhom' => $nhom,
        ]);
    }

    public function getListNhom(FrmSearchNhomRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var NhomService $nhomService */
        $nhomService = app(NhomService::class);
        try {
            $filter = $request->toFilter();
            $result = $nhomService->getListNhom($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getAllNhom(FrmSearchNhomRequest $request): JsonResponse {
        /** @var NhomService $nhomService */
        $nhomService = app(NhomService::class);
        try {
            $filter = $request->toFilter();
            $result = $nhomService->getAllNhom($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreNhomRequest $request): JsonResponse {
        /** @var NhomService $nhomService */
        $nhomService = app(NhomService::class);
        try {
            $data = $request->validated();
            $result = $nhomService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function addCanboToNhom(FrmAddCanboToNhomRequest $request, int $id): JsonResponse {
        /** @var NhomService $nhomService */
        $nhomService = app(NhomService::class);
        try {
            $data = $request->toPayload();
            $result = $nhomService->addCanboToNhom($id, $data['listIdUser']);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var NhomService $nhomService */
        $nhomService = app(NhomService::class);
        try {
            $result = $nhomService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
