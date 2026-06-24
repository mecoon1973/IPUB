<?php

namespace Modules\legalDeposit\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\legalDeposit\Request\FrmSearchPhieuNhapLCRequest;
use Modules\legalDeposit\Request\FrmStorePhieuNhapLCRequest;
use Modules\legalDeposit\Service\PhieuNhapLCService;

class PhieuNhapLCController extends Controller {

    public function viewManagePhieuNhapLC(Request $request): View {
        return view('legalDeposit::viewManagePhieuNhapLC');
    }

    public function viewStorePhieuNhapLC(Request $request, ?int $id = null): View {
        /** @var PhieuNhapLCService $phieuNhapLCService */
        $phieuNhapLCService = app(PhieuNhapLCService::class);
        $phieuNhapLC = $id ? $phieuNhapLCService->findOne("no-cache",['id' => $id]) : null;
        return view('legalDeposit::viewStorePhieuNhapLC', [
            'phieuNhapLC' => $phieuNhapLC,
        ]);
    }

    public function getPaginate(FrmSearchPhieuNhapLCRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var PhieuNhapLCService $phieuNhapLCService */
        $phieuNhapLCService = app(PhieuNhapLCService::class);
        try {
            $filter = $request->toFilter();
            $result = $phieuNhapLCService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchPhieuNhapLCRequest $request): JsonResponse {
        /** @var PhieuNhapLCService $phieuNhapLCService */
        $phieuNhapLCService = app(PhieuNhapLCService::class);
        try {
            $filter = $request->toFilter();
            $result = $phieuNhapLCService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStorePhieuNhapLCRequest $request): JsonResponse {
        /** @var PhieuNhapLCService $phieuNhapLCService */
        $phieuNhapLCService = app(PhieuNhapLCService::class);
        try {
            $data = $request->validated();
            $result = $phieuNhapLCService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var PhieuNhapLCService $phieuNhapLCService */
        $phieuNhapLCService = app(PhieuNhapLCService::class);
        try {
            $result = $phieuNhapLCService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
