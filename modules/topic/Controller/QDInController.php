<?php

namespace Modules\Topic\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\System\Traits\TraitsGetData;
use Modules\Topic\Request\FrmSearchQDInRequest;
use Modules\Topic\Request\FrmStoreQDInRequest;
use Modules\Topic\Service\PhieuDkDetaiService;
use Modules\Topic\Service\QDInService;
use Modules\User\Model\User;

class QDInController extends Controller {
    use TraitsGetData;

    public function viewManageQDIn(Request $request): View {
        return view('topic::viewManageQDIn');
    }

    public function viewStoreQDIn(Request $request, ?int $id = null): View {
        /** @var User $user */
        $user = Auth::user();

        return view('topic::viewStoreQDIn');
    }

    public function getPaginate(FrmSearchQDInRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var QDInService $qdInService */
        $qdInService = app(QDInService::class);
        try {
            $filter = $request->toFilter();
            $result = $qdInService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

        public function getList(FrmSearchQDInRequest $request): JsonResponse {
        /** @var QDInService $qdInService */
        $qdInService = app(QDInService::class);
        try {
            $filter = $request->toFilter();
            $result = $qdInService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreQDInRequest $request): JsonResponse {
        /** @var QDInService $qdInService */
        $qdInService = app(QDInService::class);
        try {
            $data = $request->validated();
            $result = $qdInService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $qdInService = app(QDInService::class);
        try {
            $result = $qdInService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
