<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchLopRequest;
use Modules\System\Request\FrmStoreLopRequest;
use Modules\System\Service\LopService;

class LopController extends Controller {

    public function viewManageLop(Request $request): View {
        return view('system::viewManageLop');
    }

    public function viewStoreLop(Request $request, ?int $id = null): View {
        /** @var LopService $lopService */
        $lopService = app(LopService::class);
        $lop = $id ? $lopService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreLop', [
            'lop' => $lop,
        ]);
    }

    public function getPaginateLop(FrmSearchLopRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var LopService $lopService */
        $lopService = app(LopService::class);
        try {
            $filter = $request->toFilter();
            $result = $lopService->getPaginateLop($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getListLop(FrmSearchLopRequest $request): JsonResponse {
        /** @var LopService $lopService */
        $lopService = app(LopService::class);
        try {
            $filter = $request->toFilter();
            $result = $lopService->getListLop($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreLopRequest $request): JsonResponse {
        /** @var LopService $lopService */
        $lopService = app(LopService::class);
        try {
            $data = $request->validated();
            $result = $lopService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var LopService $lopService */
        $lopService = app(LopService::class);
        try {
            $result = $lopService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
