<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchDonviLCRequest;
use Modules\System\Request\FrmStoreDonviLCRequest;
use Modules\System\Service\DonviLCService;
use Modules\System\Traits\TraitsGetData;

class DonviLCController extends Controller {
    use TraitsGetData;

    public function viewManageDonviLC(Request $request): View {
        return view("system::viewManageDonviLC");
    }

    public function viewStoreDonviLC(Request $request, ?int $id = null): View {

        /** @var DonviLCService $DonviLCService */
        $DonviLCService = app(DonviLCService::class);
        $DonviLC = $id ? $DonviLCService->findOne("no-cache",["id" => $id]) : null;
        $dataView = $this->getDataView(["listLoaiXbpLc"]);
        $dataView["DonviLC"] = $DonviLC;
        return view("system::viewStoreDonviLC", $dataView);
    }

    public function getPaginate(FrmSearchDonviLCRequest $request, string $page = "page-1"): JsonResponse {
        /** @var DonviLCService $DonviLCService */
        $DonviLCService = app(DonviLCService::class);
        try {
            $filter = $request->toFilter();
            $result = $DonviLCService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchDonviLCRequest $request): JsonResponse {
        /** @var DonviLCService $DonviLCService */
        $DonviLCService = app(DonviLCService::class);
        try {
            $filter = $request->toFilter();
            $result = $DonviLCService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreDonviLCRequest $request): JsonResponse {
        /** @var DonviLCService $DonviLCService */
        $DonviLCService = app(DonviLCService::class);
        try {
            $data = $request->validated();
            $result = $DonviLCService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var DonviLCService $DonviLCService */
        $DonviLCService = app(DonviLCService::class);
        try {
            $result = $DonviLCService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
