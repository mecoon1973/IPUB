<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchChucnangRequest;
use Modules\System\Request\FrmStoreChucnangRequest;
use Modules\System\Service\ChucnangService;
use Modules\System\Service\PhanheService;

class ChucnangController extends Controller {

    public function viewManageChucnang(Request $request): View {
        // /** @var ChucnangService $chucnangService */
        // $chucnangService = app(ChucnangService::class);
        // $listChucnang = $chucnangService->getDataTreeHearder();
        return view('system::viewManageChucnang');
    }

    public function viewStoreChucnang(Request $request, ?int $id = null): View {
        $parentId = $request->query('parentId', 0);
        if($parentId) {
            $parentId = (int) $parentId;
        }
        /** @var ChucnangService $chucnangService */
        $chucnangService = app(ChucnangService::class);
        $chucnang = $id ? $chucnangService->findOne("no-cache",['id' => $id]) : null;

        /** @var PhanheService $phanheService */
        $phanheService = app(PhanheService::class);
        $listPhanhe = $phanheService->getAllPhanhe();
        return view('system::viewStoreChucnang', [
            'chucnang' => $chucnang,
            'parentId' => $parentId,
            'listPhanhe' => $listPhanhe,
        ]);
    }

    public function getAllChucnang(FrmSearchChucnangRequest $request): JsonResponse {
        /** @var ChucnangService $chucnangService */
        $chucnangService = app(ChucnangService::class);
        try {
            $filter = $request->toFilter();
            $result = $chucnangService->getAllChucnang($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreChucnangRequest $request): JsonResponse {
        /** @var ChucnangService $chucnangService */
        $chucnangService = app(ChucnangService::class);
        try {
            $payload = $request->toPayload();
            $result = $chucnangService->store($payload);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var ChucnangService $chucnangService */
        $chucnangService = app(ChucnangService::class);
        try {
            $result = $chucnangService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

}
