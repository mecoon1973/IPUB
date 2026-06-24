<?php

namespace Modules\System\Controller;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\System\Service\PhanheService;

class PhanheController extends Controller {

    public function getAllPhanhe(Request $request): JsonResponse {
        /** @var PhanheService $phanheService */
        $phanheService = app(PhanheService::class);
        $result = $phanheService->getAllPhanhe();
        return response()->json($result, 200);
    }

}
