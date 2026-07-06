<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchTemplateExcelRequest;
use Modules\System\Request\FrmStoreTemplateExcelRequest;
use Modules\System\Service\TemplateExcelService;

class TemplateExcelController extends Controller {

    public function viewManageTemplateExcel(Request $request): View {
        return view("system::viewManageTemplateExcel");
    }

    public function viewStoreTemplateExcel(Request $request, ?int $id = null): View {
        /** @var TemplateExcelService $TemplateExcelService */
        $TemplateExcelService = app(TemplateExcelService::class);
        $TemplateExcel = $id ? $TemplateExcelService->findOne("no-cache",["id" => $id]) : null;
        return view("system::viewStoreTemplateExcel", [
            "TemplateExcel" => $TemplateExcel,
        ]);
    }

    public function getPaginate(FrmSearchTemplateExcelRequest $request, string $page = "page-1"): JsonResponse {
        /** @var TemplateExcelService $TemplateExcelService */
        $TemplateExcelService = app(TemplateExcelService::class);
        try {
            $filter = $request->toFilter();
            $result = $TemplateExcelService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchTemplateExcelRequest $request): JsonResponse {
        /** @var TemplateExcelService $TemplateExcelService */
        $TemplateExcelService = app(TemplateExcelService::class);
        try {
            $filter = $request->toFilter();
            $result = $TemplateExcelService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreTemplateExcelRequest $request): JsonResponse {
        /** @var TemplateExcelService $TemplateExcelService */
        $TemplateExcelService = app(TemplateExcelService::class);
        try {
            $data = $request->validated();
            $result = $TemplateExcelService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var TemplateExcelService $TemplateExcelService */
        $TemplateExcelService = app(TemplateExcelService::class);
        try {
            $result = $TemplateExcelService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
