<?php

namespace Modules\Topic\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Topic\Request\FrmSearchPhieuChuyenBanThaoRequest;
use Modules\Topic\Request\FrmStorePhieuChuyenBanThaoRequest;
use Modules\Topic\Service\PhieuChuyenBanThaoService;

class PhieuChuyenBanThaoController extends Controller {

    public function viewManagePhieuChuyenBanThao(Request $request): View {
        return view("topic::viewManagePhieuChuyenBanThao");
    }

    public function viewStorePhieuChuyenBanThao(Request $request, ?int $id = null): View {
        /** @var PhieuChuyenBanThaoService $PhieuChuyenBanThaoService */
        $PhieuChuyenBanThaoService = app(PhieuChuyenBanThaoService::class);
        $PhieuChuyenBanThao = $id ? $PhieuChuyenBanThaoService->findOne("no-cache",["id" => $id]) : null;
        return view("topic::viewStorePhieuChuyenBanThao", [
            "PhieuChuyenBanThao" => $PhieuChuyenBanThao,
        ]);
    }

    public function getPaginate(FrmSearchPhieuChuyenBanThaoRequest $request, string $page = "page-1"): JsonResponse {
        /** @var PhieuChuyenBanThaoService $PhieuChuyenBanThaoService */
        $PhieuChuyenBanThaoService = app(PhieuChuyenBanThaoService::class);
        try {
            $filter = $request->toFilter();
            $result = $PhieuChuyenBanThaoService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchPhieuChuyenBanThaoRequest $request): JsonResponse {
        /** @var PhieuChuyenBanThaoService $PhieuChuyenBanThaoService */
        $PhieuChuyenBanThaoService = app(PhieuChuyenBanThaoService::class);
        try {
            $filter = $request->toFilter();
            $result = $PhieuChuyenBanThaoService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStorePhieuChuyenBanThaoRequest $request): JsonResponse {
        /** @var PhieuChuyenBanThaoService $PhieuChuyenBanThaoService */
        $PhieuChuyenBanThaoService = app(PhieuChuyenBanThaoService::class);
        try {
            $data = $request->validated();
            $result = $PhieuChuyenBanThaoService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var PhieuChuyenBanThaoService $PhieuChuyenBanThaoService */
        $PhieuChuyenBanThaoService = app(PhieuChuyenBanThaoService::class);
        try {
            $result = $PhieuChuyenBanThaoService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception->getMessage(),
            ], 500);
        }
    }
}
        