<?php

namespace Modules\Topic\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Traits\TraitsGetData;
use Modules\Topic\Request\FrmSearchPhieuChuyenBanThaoRequest;
use Modules\Topic\Request\FrmStorePhieuChuyenBanThaoRequest;
use Modules\Topic\Service\PhieuChuyenBanThaoService;
use Modules\User\Service\UserService;

class PhieuChuyenBanThaoController extends Controller {
    use TraitsGetData;

    public function viewManagePhieuChuyenBanThao(Request $request): View {
        $dataView = $this->getDataView(['listDonvi']);
        return view("topic::viewManagePhieuChuyenBanThao", $dataView);
    }

    public function viewStorePhieuChuyenBanThao(Request $request, ?int $id = null): View {
        $dataView = $this->getDataView(['listMangsach', 'listDonvi']);
        /** @var PhieuChuyenBanThaoService $PhieuChuyenBanThaoService */
        $PhieuChuyenBanThaoService = app(PhieuChuyenBanThaoService::class);
        $PhieuChuyenBanThao = $id ? $PhieuChuyenBanThaoService->findOne("no-cache", ["id" => $id]) : null;
        if ($PhieuChuyenBanThao) {
            $PhieuChuyenBanThao->load(['sach', 'donvi', 'nguoiKy']);
        }
        /** @var UserService $userService */
        $userService = app(UserService::class);
        return view("topic::viewStorePhieuChuyenBanThao", array_merge($dataView, [
            "PhieuChuyenBanThao" => $PhieuChuyenBanThao,
            "listBTV" => $userService->getListBTV(),
        ]));
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
            $data = $request->toPayload();
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
