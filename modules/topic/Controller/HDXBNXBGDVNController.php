<?php

namespace Modules\Topic\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\System\Traits\TraitsGetData;
use Modules\Topic\Request\FrmLuuXetDuyetHDXBNXBGDVNRequest;
use Modules\Topic\Request\FrmPhanCongDocDuyetHDXBNXBGDVNRequest;
use Modules\Topic\Request\FrmSearchHDXBNXBGDVNRequest;
use Modules\Topic\Request\FrmSearchXetDuyetHDXBNXBGDVNRequest;
use Modules\Topic\Service\HDXBNXBGDVNService;
use Modules\Topic\Service\NX_CanboDetaiService;
use Illuminate\Support\Facades\Auth;

class HDXBNXBGDVNController extends Controller {
    use TraitsGetData;

    public function viewManageHDXBNXBGDVN(Request $request): View {
        $dataView = $this->getDataView(["listDonvi", "listMangsach", "mapTrangThai"]);
        /** @var \Modules\User\Service\UserService $userService */
        $userService = app(\Modules\User\Service\UserService::class);
        $dataView['listBTV'] = $userService->getListBTV();
        return view('topic::viewManageHDXBNXBGDVN', $dataView);
    }

    public function getPaginate(FrmSearchHDXBNXBGDVNRequest $request, string $page = 'page-1'): JsonResponse {
        /** @var HDXBNXBGDVNService $hdxbNxbgdvnService */
        $hdxbNxbgdvnService = app(HDXBNXBGDVNService::class);
        try {
            $filter = $request->toFilter();
            $result = $hdxbNxbgdvnService->getPaginate($filter, $page);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getList(FrmSearchHDXBNXBGDVNRequest $request): JsonResponse {
        /** @var HDXBNXBGDVNService $hdxbNxbgdvnService */
        $hdxbNxbgdvnService = app(HDXBNXBGDVNService::class);
        try {
            $filter = $request->toFilter();
            $result = $hdxbNxbgdvnService->getList($filter);
            return response()->json($result, 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function phanCongDocDuyet(FrmPhanCongDocDuyetHDXBNXBGDVNRequest $request): JsonResponse {
        /** @var HDXBNXBGDVNService $hdxbNxbgdvnService */
        $hdxbNxbgdvnService = app(HDXBNXBGDVNService::class);
        try {
            $result = $hdxbNxbgdvnService->phanCongDocDuyet(
                $request->getIdsDeTai(),
                $request->getIdCanBo()
            );
            return response()->json(['success' => true, 'count' => $result], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getListXetDuyet(FrmSearchXetDuyetHDXBNXBGDVNRequest $request): JsonResponse {
        /** @var NX_CanboDetaiService $nxCanboDetaiService */
        $nxCanboDetaiService = app(NX_CanboDetaiService::class);

        $result = $nxCanboDetaiService->getListXetDuyet($request->toFilter());
        return response()->json($result, 200);

    }

    public function luuXetDuyetDeTai(FrmLuuXetDuyetHDXBNXBGDVNRequest $request): JsonResponse {
        /** @var NX_CanboDetaiService $nxCanboDetaiService */
        $nxCanboDetaiService = app(NX_CanboDetaiService::class);

        $count = $nxCanboDetaiService->luuXetDuyetDeTai(
            $request->getItems(),
            $this->resolveIdCanBoNguoiDuyet()
        );
        return response()->json(['success' => true, 'count' => $count], 200);

    }

    private function resolveIdCanBoNguoiDuyet(): int {
        /** @var \Modules\User\Model\User|null $user */
        $user = Auth::user();
        if (!$user) {
            throw new Exception('Người dùng chưa đăng nhập');
        }
        return (int) $user->_id;
    }
}
