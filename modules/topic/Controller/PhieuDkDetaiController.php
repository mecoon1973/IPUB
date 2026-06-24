<?php

namespace Modules\Topic\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\System\Service\DonviService;
use Modules\System\Traits\TraitsGetData;
use Modules\Topic\Request\FrmSearchPhieuDkDetaiRequest;
use Modules\Topic\Request\FrmStorePhieuDkDetaiRequest;
use Modules\Topic\Service\PhieuDkDetaiService;
use Modules\User\Model\User;
use Modules\User\Service\UserService;

class PhieuDkDetaiController extends Controller {
    use TraitsGetData;

    public function viewManagePhieuDkDetai(Request $request): View {
        $dataView = $this->getDataView(["listDonvi", "mapTrangThai", "listMangsach", "listDoituong"]);
        return view('topic::viewManagePhieuDkDetai', $dataView);
    }

    public function viewTaiBanPhieuDkDetai(Request $request): View {
        $dataView = $this->getDataView(["listDonvi", "listMangsach", "listDonvi"]);
        return view('topic::viewTaiBanPhieuDkDetai', $dataView);
    }

    public function viewChuyenKeHoachPhieuDkDetai(Request $request): View {
        $dataView = $this->getDataView(["listDonvi", "listMangsach", "listDonvi"]);
        return view('topic::viewChuyenKeHoachPhieuDkDetai', $dataView);
    }

    public function viewStorePhieuDkDetai(Request $request, ?int $id = null): View {
        /** @var User $user */
        $user = Auth::user();

        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $phieuDkDetaiService = app(PhieuDkDetaiService::class);
        $phieuDkDetai = $id ? $phieuDkDetaiService->findOne("no-cache",['id' => $id]) : null;
        $dataView = $this->getDataView(["listDoituong", "listMangsach", "mapTrangThai", "listLop", "listMonhoc", "listBosach", "listTusach"]);
        /** tìm đơn vị của phiếu đăng ký đề tài */
        $idDonvi = 0;
        if($phieuDkDetai){
            $idDonvi = $phieuDkDetai->ID_DonVi;
        }else{
            if($user && isset($user->ID_DonVi)){
                $idDonvi = $user->ID_DonVi;
            }
        }
        /** @var DonviService $donviService */
        $donviService = app(DonviService::class);
        $donvi = $idDonvi ? $donviService->findOne("no-cache",['id' => $idDonvi]) : null;
        //

        //
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $listBTV = $userService->getListBTV();
        //
        $dataView['Donvi'] = $donvi;
        $dataView['listBTV'] = $listBTV;
        $dataView['phieuDkDetai'] = $phieuDkDetai;
        return view('topic::viewStorePhieuDkDetai', $dataView);
    }

    public function getPaginatePhieuDkDetai(FrmSearchPhieuDkDetaiRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $phieuDkDetaiService = app(PhieuDkDetaiService::class);
        try {
            $filter = $request->toFilter();
            $result = $phieuDkDetaiService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

        public function getListPhieuDkDetai(FrmSearchPhieuDkDetaiRequest $request): JsonResponse {
        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $phieuDkDetaiService = app(PhieuDkDetaiService::class);
        try {
            $filter = $request->toFilter();
            $result = $phieuDkDetaiService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(FrmStorePhieuDkDetaiRequest $request): JsonResponse {
        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $phieuDkDetaiService = app(PhieuDkDetaiService::class);
        try {
            $data = $request->validated();
            $result = $phieuDkDetaiService->store($data);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, int $id): JsonResponse {
        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $phieuDkDetaiService = app(PhieuDkDetaiService::class);
        try {
            $result = $phieuDkDetaiService->delete($id);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
