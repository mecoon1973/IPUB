<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Object\FilterMangsachCXB;
use Modules\System\Request\FrmSearchMangsachCXBRequest;
use Modules\System\Request\FrmStoreMangsachCXBRequest;
use Modules\System\Service\MangsachCXBService;

class MangsachCXBController extends Controller {

    public function viewManageMangsachCXB(Request $request): View {
        return view('system::viewManageMangsachCXB');
    }

    public function viewStoreMangsachCXB(Request $request, ?int $id = null): View {
        /** @var MangsachCXBService $mangsachService */
        $mangsachCXBService = app(MangsachCXBService::class);
        $mangsachCXB = $id ? $mangsachCXBService->findOne("no-cache",['id' => $id]) : null;
        return view('system::viewStoreMangsachCXB', [
            'mangsachCXB' => $mangsachCXB,
        ]);
    }

    public function getPaginate(FrmSearchMangsachCXBRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var MangsachCXBService $mangsachCXBService */
        $mangsachCXBService = app(MangsachCXBService::class);
        try {
            /** @var FilterMangsachCXB $filter */
            $filter = $request->toFilter();
            $result = $mangsachCXBService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchMangsachCXBRequest $request): JsonResponse {
        /** @var MangsachCXBService $mangsachCXBService */
        $mangsachCXBService = app(MangsachCXBService::class);
        try {
            /** @var FilterMangsachCXB $filter */
            $filter = $request->toFilter();
            $result = $mangsachCXBService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreMangsachCXBRequest $request): JsonResponse {
        /** @var MangsachCXBService $mangsachCXBService */
        $mangsachCXBService = app(MangsachCXBService::class);
        try {
            $data = $request->validated();
            $result = $mangsachCXBService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var MangsachCXBService $mangsachCXBService */
        $mangsachCXBService = app(MangsachCXBService::class);
        try {
            $result = $mangsachCXBService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
