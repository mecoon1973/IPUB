<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchChuyenMonRequest;
use Modules\System\Request\FrmSearchTusachRequest;
use Modules\System\Request\FrmStoreChuyenmonRequest;
use Modules\System\Request\FrmStoreTusachRequest;
use Modules\System\Service\ChuyenmonService;
use Modules\System\Service\TusachService;

class ChuyenmonController extends Controller {

    public function viewManageChuyenmon(Request $request): View {
        return view('system::viewManageChuyenmon');
    }

    public function viewStoreChuyenmon(Request $request, ?int $id = null): View {
        /** @var ChuyenmonService $chuyenmonService */
        $chuyenmonService = app(ChuyenmonService::class);
        $chuyenmon = $id ? $chuyenmonService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreChuyenmon', [
            'chuyenmon' => $chuyenmon,
        ]);
    }

    public function getPaginateChuyenmon(FrmSearchChuyenMonRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var ChuyenmonService $chuyenmonService */
        $chuyenmonService = app(ChuyenmonService::class);
        try {
            $filter = $request->toFilter();
            $result = $chuyenmonService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchChuyenMonRequest $request): JsonResponse {
        /** @var ChuyenmonService $chuyenmonService */
        $chuyenmonService = app(ChuyenmonService::class);
        try {
            $filter = $request->toFilter();
            $result = $chuyenmonService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreChuyenmonRequest $request): JsonResponse {
        /** @var ChuyenmonService $chuyenmonService */
        $chuyenmonService = app(ChuyenmonService::class);
        try {
            $data = $request->validated();
            $result = $chuyenmonService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var ChuyenmonService $chuyenmonService */
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
