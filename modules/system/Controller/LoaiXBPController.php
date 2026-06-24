<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\System\Service\HDXBService;
use Modules\System\Request\FrmSearchLoaiXBPRequest;
use Modules\System\Service\LoaiXBPService;
use Modules\System\Request\FrmStoreLoaiXBPRequest;

class LoaiXBPController extends Controller {

    public function viewManageLoaiXBP(Request $request): View {
        return view('system::viewManageLoaiXBP');
    }

    public function viewStoreLoaiXBP(Request $request, ?int $id = null): View {
        /** @var LoaiXBPService $loaiXBPService */
        $loaiXBPService = app(LoaiXBPService::class);
        $loaiXBP = $loaiXBPService->findOne("no-cache",['id' => $id]);
        return view('system::viewStoreLoaiXBP', [
            'loaiXBP' => $loaiXBP,
        ]);
    }

    public function getAllLoaiXBP(FrmSearchLoaiXBPRequest $request): JsonResponse {
        /** @var LoaiXBPService $loaiXBPService */
        $loaiXBPService = app(LoaiXBPService::class);
        try {
            $filter = $request->toFilter();
            $result = $loaiXBPService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getPaginateLoaiXBP(FrmSearchLoaiXBPRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var LoaiXBPService $loaiXBPService */
        $loaiXBPService = app(LoaiXBPService::class);
        try {
            $filter = $request->toFilter();
            $result = $loaiXBPService->getPaginate($filter, $page);
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
    public function store(FrmStoreLoaiXBPRequest $request): JsonResponse {
        /** @var LoaiXBPService $loaiXBPService */
        $loaiXBPService = app(LoaiXBPService::class);
        try {
            $payload = $request->toPayload();
            $hdxb = $loaiXBPService->store($payload);
            return response()->json($hdxb, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
