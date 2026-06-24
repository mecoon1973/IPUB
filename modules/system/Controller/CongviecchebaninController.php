<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchCongviecchebaninRequest;
use Modules\System\Request\FrmStoreCongviecchebaninRequest;
use Modules\System\Service\CongviecchebaninService;

class CongviecchebaninController extends Controller {

    public function viewManageCongviecchebanin(Request $request): View {
        return view('system::viewManageCongviecchebanin');
    }

    public function viewStoreCongviecchebanin(Request $request, ?int $id = null): View {
        /** @var CongviecchebaninService $congviecchebaninService */
        $congviecchebaninService = app(CongviecchebaninService::class);
        $congviecchebanin = $id ? $congviecchebaninService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreCongviecchebanin', [
            'congviecchebanin' => $congviecchebanin,
        ]);
    }

    public function getPaginate(FrmSearchCongviecchebaninRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var CongviecchebaninService $congviecchebaninService */
        $congviecchebaninService = app(CongviecchebaninService::class);
        try {
            $filter = $request->toFilter();
            $result = $congviecchebaninService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchCongviecchebaninRequest $request): JsonResponse {
        /** @var CongviecchebaninService $congviecchebaninService */
        $congviecchebaninService = app(CongviecchebaninService::class);
        try {
            $filter = $request->toFilter();
            $result = $congviecchebaninService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreCongviecchebaninRequest $request): JsonResponse {
        /** @var CongviecchebaninService $congviecchebaninService */
        $congviecchebaninService = app(CongviecchebaninService::class);
        try {
            $data = $request->validated();
            $result = $congviecchebaninService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var CongviecchebaninService $congviecchebaninService */
        $congviecchebaninService = app(CongviecchebaninService::class);
        try {
            $result = $congviecchebaninService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
