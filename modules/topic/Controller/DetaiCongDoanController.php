<?php

namespace Modules\Topic\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\System\Traits\TraitsGetData;
use Modules\Topic\Request\FrmSearchDetaiCongDoanRequest;
use Modules\Topic\Request\FrmStoreDetaiCongDoanRequest;
use Modules\Topic\Service\CT_Detai_CongDoanService;

class DetaiCongDoanController extends Controller {
    use TraitsGetData;


    public function getPaginate(FrmSearchDetaiCongDoanRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var CT_Detai_CongDoanService $detaiCongDoanService */
        $detaiCongDoanService = app(CT_Detai_CongDoanService::class);
        try {
            $filter = $request->toFilter();
            $result = $detaiCongDoanService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchDetaiCongDoanRequest $request): JsonResponse {
        /** @var CT_Detai_CongDoanService $detaiCongDoanService */
        $detaiCongDoanService = app(CT_Detai_CongDoanService::class);
        try {
            $filter = $request->toFilter();
            $result = $detaiCongDoanService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreDetaiCongDoanRequest $request): JsonResponse {
        /** @var CT_Detai_CongDoanService $detaiCongDoanService */
        $detaiCongDoanService = app(CT_Detai_CongDoanService::class);
        try {
            $data = $request->validated();
            $result = $detaiCongDoanService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var CT_Detai_CongDoanService $detaiCongDoanService */
        $detaiCongDoanService = app(CT_Detai_CongDoanService::class);
        try {
            $result = $detaiCongDoanService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
