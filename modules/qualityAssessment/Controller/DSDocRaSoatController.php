<?php

namespace Modules\QualityAssessment\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\QualityAssessment\Request\FrmSearchDSDocRaSoatRequest;
use Modules\QualityAssessment\Request\FrmStoreDSDocRaSoatRequest;
use Modules\QualityAssessment\Service\DSDocRaSoatService;
use Modules\System\Traits\TraitsGetData;

class DSDocRaSoatController extends Controller {
    use TraitsGetData;
    public function viewManageDSDocRaSoat(Request $request): View {

        return view("qualityAssessment::viewManageDSDocRaSoat");
    }

    public function viewStoreDSDocRaSoat(Request $request, ?int $id = null): View {
        $dataView = $this->getDataView(["listDonvi", "listMangsach"]);
        /** @var DSDocRaSoatService $DSDocRaSoatService */
        $DSDocRaSoatService = app(DSDocRaSoatService::class);
        $DSDocRaSoat = $id ? $DSDocRaSoatService->findOne("no-cache",["id" => $id]) : null;
        $dataView["DSDocRaSoat"] = $DSDocRaSoat;
        return view("qualityAssessment::viewStoreDSDocRaSoat", $dataView);
    }

    public function getPaginate(FrmSearchDSDocRaSoatRequest $request, string $page = "page-1"): JsonResponse {
        /** @var DSDocRaSoatService $DSDocRaSoatService */
        $DSDocRaSoatService = app(DSDocRaSoatService::class);
        try {
            $filter = $request->toFilter();
            $result = $DSDocRaSoatService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchDSDocRaSoatRequest $request): JsonResponse {
        /** @var DSDocRaSoatService $DSDocRaSoatService */
        $DSDocRaSoatService = app(DSDocRaSoatService::class);
        try {
            $filter = $request->toFilter();
            $result = $DSDocRaSoatService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreDSDocRaSoatRequest $request): JsonResponse {
        /** @var DSDocRaSoatService $DSDocRaSoatService */
        $DSDocRaSoatService = app(DSDocRaSoatService::class);
        try {
            $data = $request->validated();
            $result = $DSDocRaSoatService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var DSDocRaSoatService $DSDocRaSoatService */
        $DSDocRaSoatService = app(DSDocRaSoatService::class);
        try {
            $result = $DSDocRaSoatService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
