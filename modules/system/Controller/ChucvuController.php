<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchChucvuRequest;
use Modules\System\Request\FrmSearchChuyenMonRequest;
use Modules\System\Request\FrmSearchTusachRequest;
use Modules\System\Request\FrmStoreChucvuRequest;
use Modules\System\Request\FrmStoreChuyenmonRequest;
use Modules\System\Request\FrmStoreTusachRequest;
use Modules\System\Service\ChucvuService;
use Modules\System\Service\ChuyenmonService;
use Modules\System\Service\TusachService;

class ChucvuController extends Controller {

    public function viewManageChucvu(Request $request): View {
        return view('system::viewManageChucvu');
    }

    public function viewStoreChucvu(Request $request, ?int $id = null): View {
        /** @var ChucvuService $chucvuService */
        $chucvuService = app(ChucvuService::class);
        $chucvu = $id ? $chucvuService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreChucvu', [
            'chucvu' => $chucvu,
        ]);
    }

    public function getPaginateChucvu(FrmSearchChucvuRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var ChucvuService $chucvuService */
        $chucvuService = app(ChucvuService::class);
        try {
            $filter = $request->toFilter();
            $result = $chucvuService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getListChucvu(FrmSearchChucvuRequest $request): JsonResponse {
        /** @var ChucvuService $chucvuService */
        $chucvuService = app(ChucvuService::class);
        try {
            $filter = $request->toFilter();
            $result = $chucvuService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreChucvuRequest $request): JsonResponse {
        /** @var ChucvuService $chucvuService */
        $chucvuService = app(ChucvuService::class);
        try {
            $data = $request->validated();
            $result = $chucvuService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var ChucvuService $chucvuService */
        $chuyenmonService = app(ChuyenmonService::class);
        try {
            $result = $chuyenmonService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
