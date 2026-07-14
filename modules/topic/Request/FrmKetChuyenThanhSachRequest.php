<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmKetChuyenThanhSachRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'idPhieu' => 'required|integer',
            'listIdDeTai' => 'required|array|min:1',
            'listIdDeTai.*' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'idPhieu.required' => 'Không xác định được phiếu trình CXB',
            'listIdDeTai.required' => 'Vui lòng chọn ít nhất một đề tài để kết chuyển',
            'listIdDeTai.min' => 'Vui lòng chọn ít nhất một đề tài để kết chuyển',
        ];
    }

    public function toPayload(): array
    {
        $listIdDeTai = $this->input('listIdDeTai', []);
        if (!is_array($listIdDeTai)) {
            $listIdDeTai = [];
        }

        return [
            'idPhieu' => (int) $this->input('idPhieu'),
            'listIdDeTai' => array_values(array_map('intval', $listIdDeTai)),
        ];
    }
}
