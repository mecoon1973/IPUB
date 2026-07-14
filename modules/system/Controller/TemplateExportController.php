<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Request\FrmSearchTemplateExportRequest;
use Modules\System\Request\FrmStoreTemplateExportRequest;
use Modules\System\Request\FrmUploadTemplateExportRequest;
use Modules\System\Service\TemplateExportService;

class TemplateExportController extends Controller {

    public function viewManageTemplateExport(Request $request): View {
        return view("system::viewManageTemplateExport");
    }

    public function viewStoreTemplateExport(Request $request, ?int $id = null): View {
        /** @var TemplateExportService $templateExportService */
        $templateExportService = app(TemplateExportService::class);
        $templateExport = $id ? $templateExportService->findOne("no-cache",["id" => $id]) : null;
        return view("system::viewStoreTemplateExport", [
            "TemplateExport" => $templateExport,
        ]);
    }

    public function getPaginate(FrmSearchTemplateExportRequest $request, string $page = "page-1"): JsonResponse {
        /** @var TemplateExportService $templateExportService */
        $templateExportService = app(TemplateExportService::class);
        try {
            $filter = $request->toFilter();
            $result = $templateExportService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchTemplateExportRequest $request): JsonResponse {
        /** @var TemplateExportService $templateExportService */
        $templateExportService = app(TemplateExportService::class);
        try {
            $filter = $request->toFilter();
            $result = $templateExportService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function uploadTemplate(FrmUploadTemplateExportRequest $request): JsonResponse {
        /** @var TemplateExportService $templateExportService */
        $templateExportService = app(TemplateExportService::class);
        try {
            $field = $request->getTemplateFileField();
            $templateUrl = $templateExportService->uploadTemplate(
                $request->getUploadedFile(),
                $request->getTemplateKey()
            );

            return response()->json([
                $field => $templateUrl,
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreTemplateExportRequest $request): JsonResponse {
        /** @var TemplateExportService $templateExportService */
        $templateExportService = app(TemplateExportService::class);
        try {
            $data = $request->validated();
            $result = $templateExportService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var TemplateExportService $templateExportService */
        $templateExportService = app(TemplateExportService::class);
        try {
            $result = $templateExportService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
