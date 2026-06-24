<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchBosachRequest;
use Modules\System\Request\FrmSearchLopRequest;
use Modules\System\Request\FrmStoreBosachRequest;
use Modules\System\Request\FrmStoreLopRequest;
use Modules\System\Service\BosachService;
use Modules\System\Service\LopService;

class BosachController extends Controller {

    public function viewManageBosach(Request $request): View {
        return view('system::viewManageBosach');
    }

    public function viewStoreBosach(Request $request, ?int $id = null): View {
        /** @var BosachService $bosachService */
        $bosachService = app(BosachService::class);
        $bosach = $id ? $bosachService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreBosach', [
            'bosach' => $bosach,
        ]);
    }

    public function getPaginateBosach(FrmSearchBosachRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var BosachService $bosachService */
        $bosachService = app(BosachService::class);
        try {
            $filter = $request->toFilter();
            $result = $bosachService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getListBosach(FrmSearchBosachRequest $request): JsonResponse {
        /** @var BosachService $bosachService */
        $bosachService = app(BosachService::class);
        try {
            $filter = $request->toFilter();
            $result = $bosachService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreBosachRequest $request): JsonResponse {
        /** @var BosachService $bosachService */
        $bosachService = app(BosachService::class);
        try {
            $data = $request->validated();
            $result = $bosachService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var BosachService $bosachService */
        $bosachService = app(BosachService::class);
        try {
            $result = $bosachService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
