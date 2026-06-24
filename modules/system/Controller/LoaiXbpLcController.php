<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchLoaiXbpLcRequest;
use Modules\System\Service\HDXBService;
use Modules\System\Request\FrmSearchLoaiXBPRequest;
use Modules\System\Request\FrmStoreLoaiXbpLcRequest;
use Modules\System\Service\LoaiXBPService;
use Modules\System\Request\FrmStoreLoaiXBPRequest;
use Modules\System\Service\LoaiXbpLcService;
use Modules\System\Traits\TraitsGetData;

class LoaiXBPLCController extends Controller {
    use TraitsGetData;
    public function viewManageLoaiXbpLc(Request $request): View {
        return view('system::viewManageLoaiXbpLc');
    }

    public function viewStoreLoaiXbpLc(Request $request, ?int $id = null): View {
        $dataView = $this->getDataView(["listDonviLC"]);
        /** @var LoaiXbpLcService $loaiXbpLcService */
        $loaiXbpLcService = app(LoaiXbpLcService::class);
        $loaiXbpLc = $loaiXbpLcService->findOne("no-cache",['id' => $id]);
        $dataView["loaiXbpLc"] = $loaiXbpLc;
        return view('system::viewStoreLoaiXbpLc', $dataView);
    }

    public function getAll(FrmSearchLoaiXbpLcRequest $request): JsonResponse {
        /** @var LoaiXbpLcService $loaiXbpLcService */
        $loaiXbpLcService = app(LoaiXbpLcService::class);
        try {
            $filter = $request->toFilter();
            $result = $loaiXbpLcService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getPaginate(FrmSearchLoaiXbpLcRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var LoaiXbpLcService $loaiXbpLcService */
        $loaiXbpLcService = app(LoaiXbpLcService::class);
        try {
            $filter = $request->toFilter();
            $result = $loaiXbpLcService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Tạo mới đơn vị — validate qua FrmStoreHDXBRequest, chỉ dùng payload đã validate.
     */
    public function store(FrmStoreLoaiXbpLcRequest $request): JsonResponse {
        /** @var LoaiXbpLcService $loaiXbpLcService */
        $loaiXbpLcService = app(LoaiXbpLcService::class);
        try {
            $payload = $request->toPayload();
            $hdxb = $loaiXbpLcService->store($payload);
            return response()->json($hdxb, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
