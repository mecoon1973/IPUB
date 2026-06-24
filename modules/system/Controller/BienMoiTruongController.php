<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchBienMoiTruongRequest;
use Modules\System\Request\FrmStoreBienMoiTruongRequest;
use Modules\System\Service\BienMoiTruongService;

class BienMoiTruongController extends Controller {

    public function viewManageBienMoiTruong(Request $request): View {
        return view('system::viewManageBienMoiTruong');
    }

    public function viewStoreBienMoiTruong(Request $request, ?int $id = null): View {
        /** @var BienMoiTruongService $bienMoiTruongService */
        $bienMoiTruongService = app(BienMoiTruongService::class);
        $bienMoiTruong = $id ? $bienMoiTruongService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreBienMoiTruong', [
            'bienMoiTruong' => $bienMoiTruong,
        ]);
    }

    public function getPaginate(FrmSearchBienMoiTruongRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var BienMoiTruongService $bienMoiTruongService */
        $bienMoiTruongService = app(BienMoiTruongService::class);
        try {
            $filter = $request->toFilter();
            $result = $bienMoiTruongService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchBienMoiTruongRequest $request): JsonResponse {
        /** @var BienMoiTruongService $bienMoiTruongService */
        $bienMoiTruongService = app(BienMoiTruongService::class);
        try {
            $filter = $request->toFilter();
            $result = $bienMoiTruongService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreBienMoiTruongRequest $request): JsonResponse {
        /** @var BienMoiTruongService $bienMoiTruongService */
        $bienMoiTruongService = app(BienMoiTruongService::class);
        try {
            $data = $request->validated();
            $result = $bienMoiTruongService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var BienMoiTruongService $bienMoiTruongService */
        $bienMoiTruongService = app(BienMoiTruongService::class);
        try {
            $result = $bienMoiTruongService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
