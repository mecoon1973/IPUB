<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchDoituongSNVRequest;
use Modules\System\Request\FrmStoreDoituongSNVRequest;
use Modules\System\Service\DoituongSNVService;
use Modules\System\Traits\TraitsGetData;

class DoituongSNVController extends Controller {
    use TraitsGetData;
    public function viewManageDoituongSNV(Request $request): View {
        return view("system::viewManageDoituongSNV");
    }

    public function viewStoreDoituongSNV(Request $request, ?int $id = null): View {
        /** @var DoituongSNVService $DoituongSNVService */
        $doituongSNVService = app(DoituongSNVService::class);
        $doituongSNV = $id ? $doituongSNVService->findOne("no-cache",["id" => $id]) : null;
        $dataView = $this->getDataView(["listLoaiSNV"]);
        $dataView["doituongSNV"] = $doituongSNV;
        return view("system::viewStoreDoituongSNV", $dataView);
    }

    public function getPaginate(FrmSearchDoituongSNVRequest $request, string $page = "page-1"): JsonResponse {
        /** @var DoituongSNVService $doituongSNVService */
        $doituongSNVService = app(DoituongSNVService::class);
        try {
            $filter = $request->toFilter();
            $result = $doituongSNVService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchDoituongSNVRequest $request): JsonResponse {
        /** @var DoituongSNVService $doituongSNVService */
        $doituongSNVService = app(DoituongSNVService::class);
        try {
            $filter = $request->toFilter();
            $result = $doituongSNVService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreDoituongSNVRequest $request): JsonResponse {
        /** @var DoituongSNVService $doituongSNVService */
        $doituongSNVService = app(DoituongSNVService::class);
        try {
            $data = $request->validated();
            $result = $doituongSNVService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var DoituongSNVService $doituongSNVService */
        $doituongSNVService = app(DoituongSNVService::class);
        try {
            $result = $doituongSNVService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
