<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchMonhocRequest;
use Modules\System\Request\FrmStoreMonHocRequest;
use Modules\System\Service\MonhocService;

class MonhocController extends Controller {

    public function viewManageMonhoc(Request $request): View {
        return view('system::viewManageMonhoc');
    }

    public function viewStoreMonhoc(Request $request, ?int $id = null): View {
        /** @var MonhocService $monhocService */
        $monhocService = app(MonhocService::class);
        $monhoc = $id ? $monhocService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreMonhoc', [
            'monhoc' => $monhoc,
        ]);
    }

    public function getPaginateMonhoc(FrmSearchMonhocRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var MonhocService $monhocService */
        $monhocService = app(MonhocService::class);
        try {
            $filter = $request->toFilter();
            $result = $monhocService->getPaginateMonhoc($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getListMonhoc(FrmSearchMonhocRequest $request): JsonResponse {
        /** @var MonhocService $monhocService */
        $monhocService = app(MonhocService::class);
        try {
            $filter = $request->toFilter();
            $result = $monhocService->getListMonhoc($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

        public function store(FrmStoreMonHocRequest $request): JsonResponse {
        /** @var MonhocService $monhocService */
        $monhocService = app(MonhocService::class);
        try {
            $data = $request->validated();
            $result = $monhocService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var MonhocService $monhocService */
        $monhocService = app(MonhocService::class);
        try {
            $result = $monhocService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
