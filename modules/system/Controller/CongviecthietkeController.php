<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchCongviecthietkeRequest;
use Modules\System\Request\FrmStoreCongviecthietkeRequest;
use Modules\System\Service\CongviecthietkeService;

class CongviecthietkeController extends Controller {

    public function viewManageCongviecthietke(Request $request): View {
        return view('system::viewManageCongviecthietke');
    }

    public function viewStoreCongviecthietke(Request $request, ?int $id = null): View {
        /** @var CongviecthietkeService $congviecthietkeService */
        $congviecthietkeService = app(CongviecthietkeService::class);
        $congviecthietke = $id ? $congviecthietkeService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreCongviecthietke', [
            'congviecthietke' => $congviecthietke,
        ]);
    }

    public function getPaginate(FrmSearchCongviecthietkeRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var CongviecthietkeService $congviecthietkeService */
        $congviecthietkeService = app(CongviecthietkeService::class);
        try {
            $filter = $request->toFilter();
            $result = $congviecthietkeService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchCongviecthietkeRequest $request): JsonResponse {
        /** @var CongviecthietkeService $congviecthietkeService */
        $congviecthietkeService = app(CongviecthietkeService::class);
        try {
            $filter = $request->toFilter();
            $result = $congviecthietkeService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreCongviecthietkeRequest $request): JsonResponse {
        /** @var CongviecthietkeService $congviecthietkeService */
        $congviecthietkeService = app(CongviecthietkeService::class);
        try {
            $data = $request->validated();
            $result = $congviecthietkeService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var CongviecthietkeService $congviecthietkeService */
        $congviecthietkeService = app(CongviecthietkeService::class);
        try {
            $result = $congviecthietkeService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
