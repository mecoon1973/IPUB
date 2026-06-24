<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchDonviRequest;
use Modules\System\Request\FrmSearchHDXBRequest;
use Modules\System\Request\FrmStoreDonviRequest;
use Modules\System\Request\FrmStoreHDXBRequest;
use Modules\System\Service\DonviService;
use Modules\System\Service\HDXBService;

class HDXBController extends Controller {

    public function viewManageHDXB(Request $request): View {
        return view('system::viewManageHDXB');
    }

    public function viewStoreHDXB(Request $request, ?int $id = null): View {
        /** @var HDXBService $hdxbService */
        $hdxbService = app(HDXBService::class);
        $hdxb = $hdxbService->findOne("no-cache",['id' => $id]);
        return view('system::viewStoreHDXB', [
            'hdxb' => $hdxb,
        ]);
    }

    public function getAllHDXB(FrmSearchHDXBRequest $request): JsonResponse {
        /** @var HDXBService $hdxbService */
        $hdxbService = app(HDXBService::class);
        try {
            $filter = $request->toFilter();
            $result = $hdxbService->getAllHDXB($filter);
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
    public function store(FrmStoreHDXBRequest $request): JsonResponse {
        /** @var HDXBService $hdxbService */
        $hdxbService = app(HDXBService::class);
        try {
            $payload = $request->toPayload();
            $hdxb = $hdxbService->store($payload);
            return response()->json($hdxb, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
