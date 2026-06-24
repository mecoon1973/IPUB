<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchSystemLogRequest;
use Modules\System\Service\SystemLogService;
use Modules\System\Traits\TraitsGetData;

class SystemLogController extends Controller {
    use TraitsGetData;
    public function viewManageSystemLog(Request $request): View {
        $dataView = $this->getDataView(["listDonvi"]);
        return view('system::viewManageSystemLog', $dataView);
    }

    public function getPaginate(FrmSearchSystemLogRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var SystemLogService $systemLogService */
        $systemLogService = app(SystemLogService::class);
        try {
            $filter = $request->toFilter();
            $result = $systemLogService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchSystemLogRequest $request): JsonResponse {
        /** @var SystemLogService $systemLogService */
        $systemLogService = app(SystemLogService::class);
        try {
            $filter = $request->toFilter();
            $result = $systemLogService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

}
