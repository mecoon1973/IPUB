<?php

namespace Modules\Book\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Book\Request\FrmSearchSachRequest;
use Modules\Book\Request\FrmStoreSachRequest;
use Modules\Book\Service\SachService;
use Modules\System\Traits\TraitsGetData;

class SachController extends Controller {
    use TraitsGetData;

    public function viewManageSach(Request $request): View {
        $dataView = $this->getDataView(["listDonvi", "listMangsach"]);
        return view("book::viewManageSach", $dataView);
    }

    public function viewPrintISBN(Request $request, int $id): View {
        /** @var SachService $SachService */
        $SachService = app(SachService::class);
        $sach = $SachService->findOne("no-cache", ['id' => $id]);
        if(!$sach) {
            abort(404, "Không tìm thấy sách");
        }
        return view("book::viewPrintISBN", [
            'sach' => $sach,
        ]);
    }


    public function getPaginate(FrmSearchSachRequest $request, string $page = "page-1"): JsonResponse {
        /** @var SachService $SachService */
        $SachService = app(SachService::class);
        try {
            $filter = $request->toFilter();
            $result = $SachService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchSachRequest $request): JsonResponse {
        /** @var SachService $SachService */
        $SachService = app(SachService::class);
        try {
            $filter = $request->toFilter();
            $result = $SachService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStoreSachRequest $request): JsonResponse {
        /** @var SachService $SachService */
        $SachService = app(SachService::class);
        try {
            $data = $request->validated();
            $result = $SachService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var SachService $SachService */
        $SachService = app(SachService::class);
        try {
            $result = $SachService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
