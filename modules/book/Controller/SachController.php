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

class SachController extends Controller {

    public function viewManageSach(Request $request): View {
        return view("book::viewManageSach");
    }

    public function viewStoreSach(Request $request, ?int $id = null): View {
        /** @var SachService $SachService */
        $SachService = app(SachService::class);
        $Sach = $id ? $SachService->findOne("no-cache",["id" => $id]) : null;
        return view("book::viewStoreSach", [
            "Sach" => $Sach,
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
        