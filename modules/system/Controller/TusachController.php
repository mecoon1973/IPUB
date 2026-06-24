<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchTusachRequest;
use Modules\System\Request\FrmStoreTusachRequest;
use Modules\System\Service\TusachService;

class TusachController extends Controller {

    public function viewManageTusach(Request $request): View {
        return view('system::viewManageTusach');
    }

    public function viewStoreTusach(Request $request, ?int $id = null): View {
        /** @var TusachService $tusachService */
        $tusachService = app(TusachService::class);
        $tusach = $id ? $tusachService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreTusach', [
            'tusach' => $tusach,
        ]);
    }

    public function getPaginateTusach(FrmSearchTusachRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var TusachService $tusachService */
        $tusachService = app(TusachService::class);
        try {
            $filter = $request->toFilter();
            $result = $tusachService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getListTusach(FrmSearchTusachRequest $request): JsonResponse {
        /** @var TusachService $tusachService */
        $tusachService = app(TusachService::class);
        try {
            $filter = $request->toFilter();
            $result = $tusachService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreTusachRequest $request): JsonResponse {
        /** @var TusachService $tusachService */
        $tusachService = app(TusachService::class);
        try {
            $data = $request->validated();
            $result = $tusachService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var TusachService $tusachService */
        $tusachService = app(TusachService::class);
        try {
            $result = $tusachService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
