<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Object\FilterNgoaingu;
use Modules\System\Request\FrmSearchNgoainguRequest;
use Modules\System\Request\FrmStoreNgoainguRequest;
use Modules\System\Service\NgoaiNguService;

class NgoainguController extends Controller {

    public function viewManageNgoaingu(Request $request): View {
        return view('system::viewManageNgoaingu');
    }

    public function viewStoreNgoaingu(Request $request, ?int $id = null): View {
        /** @var NgoainguService $ngoainguService */
        $ngoainguService = app(NgoainguService::class);
        $ngoaingu = $id ? $ngoainguService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreNgoaingu', [
            'ngoaingu' => $ngoaingu,
        ]);
    }

    public function getPaginate(FrmSearchNgoainguRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var NgoainguService $ngoainguService */
        $ngoainguService = app(NgoainguService::class);
        try {
            /** @var FilterNgoaingu $filter */
            $filter = $request->toFilter();
            $result = $ngoainguService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchNgoainguRequest $request): JsonResponse {
        /** @var NgoainguService $ngoainguService */
        $ngoainguService = app(NgoainguService::class);
        try {
            /** @var FilterNgoaingu $filter */
            $filter = $request->toFilter();
            $result = $ngoainguService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreNgoainguRequest $request): JsonResponse {
        /** @var NgoainguService $ngoainguService */
        $ngoainguService = app(NgoainguService::class);
        try {
            $data = $request->validated();
            $result = $ngoainguService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var NgoainguService $ngoainguService */
        $ngoainguService = app(NgoainguService::class);
        try {
            $result = $ngoainguService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
