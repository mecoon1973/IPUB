<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchDoituongRequest;
use Modules\System\Request\FrmStoreDoituongRequest;
use Modules\System\Service\DoituongService;

class DoituongController extends Controller {

    public function viewManageDoituong(Request $request): View {
        return view('system::viewManageDoituong');
    }

    public function viewStoreDoituong(Request $request, ?int $id = null): View {
        /** @var DoituongService $doituongService */
        $doituongService = app(DoituongService::class);
        $doituong = $id ? $doituongService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreDoituong', [
            'doituong' => $doituong,
        ]);
    }

    public function getPaginateDoituong(FrmSearchDoituongRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var DoituongService $doituongService */
        $doituongService = app(DoituongService::class);
        try {
            $filter = $request->toFilter();
            $result = $doituongService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getListDoituong(FrmSearchDoituongRequest $request): JsonResponse {
        /** @var DoituongService $doituongService */
        $doituongService = app(DoituongService::class);
        try {
            $filter = $request->toFilter();
            $result = $doituongService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreDoituongRequest $request): JsonResponse {
        /** @var DoituongService $doituongService */
        $doituongService = app(DoituongService::class);
        try {
            $data = $request->validated();
            $result = $doituongService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var DoituongService $doituongService */
        $doituongService = app(DoituongService::class);
        try {
            $result = $doituongService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
