<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchDonviRequest;
use Modules\System\Request\FrmStoreDonviRequest;
use Modules\System\Service\DonviService;

class DonviController extends Controller {

    public function viewManageDonvi(Request $request): View {
        return view('system::viewManageDonvi');
    }

    public function viewStoreDonvi(Request $request, ?int $id = null): View {
        $parentId = $request->query('parentId', 0);
        if($parentId) {
            $parentId = (int) $parentId;
        }
        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);
        $donvi = $donviService->findOne("no-cache",['id' => $id]);
        return view('system::viewStoreDonvi', [
            'donvi' => $donvi,
            'parentId' => $parentId,
        ]);
    }

    public function getAllDonvi(FrmSearchDonviRequest $request): JsonResponse {
        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);
        try {
            $filter = $request->toFilter();
            $result = $donviService->getAllDonvi($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Tạo mới đơn vị — validate qua FrmStoreDonviRequest, chỉ dùng payload đã validate.
     */
    public function store(FrmStoreDonviRequest $request): JsonResponse {
        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);
        try {
            $payload = $request->toPayload();
            $donvi = $donviService->store($payload);
            return response()->json($donvi, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);

        try {
            $result = $donviService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
