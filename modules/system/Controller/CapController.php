<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchCapRequest;
use Modules\System\Request\FrmStoreCapRequest;
use Modules\System\Service\CapService;

class CapController extends Controller {

    public function viewManageCap(Request $request): View {
        return view("system::viewManageCap");
    }

    public function viewStoreCap(Request $request, ?int $id = null): View {
        /** @var CapService $CapService */
        $CapService = app(CapService::class);
        $Cap = $id ? $CapService->findOne("no-cache",["id" => $id]) : null;
        return view("system::viewStoreCap", [
            "Cap" => $Cap,
        ]);
    }

    public function getPaginate(FrmSearchCapRequest $request, string $page = "page-1"): JsonResponse {
        /** @var CapService $CapService */
        $CapService = app(CapService::class);
        try {
            $filter = $request->toFilter();
            $result = $CapService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchCapRequest $request): JsonResponse {
        /** @var CapService $CapService */
        $CapService = app(CapService::class);
        try {
            $filter = $request->toFilter();
            $result = $CapService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreCapRequest $request): JsonResponse {
        /** @var CapService $CapService */
        $CapService = app(CapService::class);
        try {
            $data = $request->validated();
            $result = $CapService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var CapService $CapService */
        $CapService = app(CapService::class);
        try {
            $result = $CapService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
        