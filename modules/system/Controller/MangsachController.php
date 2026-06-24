<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Object\FilterMangsach;
use Modules\System\Request\FrmSearchMangsachRequest;
use Modules\System\Request\FrmStoreMangsachRequest;
use Modules\System\Service\MangsachService;

class MangsachController extends Controller {

    public function viewManageMangsach(Request $request): View {
        return view('system::viewManageMangsach');
    }

    public function viewStoreMangsach(Request $request, ?int $id = null): View {
        /** @var MangsachService $mangsachService */
        $mangsachService = app(MangsachService::class);
        $mangsach = $id ? $mangsachService->findOne("no-cache",['id' => $id]) : null;
        $parentId = $request->query('parentId', 0);
        if($parentId) {
            $parentId = (int) $parentId;
        }
        return view('system::viewStoreMangsach', [
            'mangsach' => $mangsach,
            'parentId' => $parentId,
        ]);
    }

    public function getPaginateMangsach(FrmSearchMangsachRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var MangsachService $mangsachService */
        $mangsachService = app(MangsachService::class);
        try {
            /** @var FilterMangsach $filter */
            $filter = $request->toFilter();
            $result = $mangsachService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getListMangsach(FrmSearchMangsachRequest $request): JsonResponse {
        /** @var MangsachService $mangsachService */
        $mangsachService = app(MangsachService::class);
        try {
            /** @var FilterMangsach $filter */
            $filter = $request->toFilter();
            $result = $mangsachService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreMangsachRequest $request): JsonResponse {
        /** @var MangsachService $mangsachService */
        $mangsachService = app(MangsachService::class);
        try {
            $data = $request->validated();
            $result = $mangsachService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var MangsachService $mangsachService */
        $mangsachService = app(MangsachService::class);
        try {
            $result = $mangsachService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
