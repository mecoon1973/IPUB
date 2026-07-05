<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmPhanCongDocDuyetHDXBNXBGDVNRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|min:1',
            'idCanBo' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'ids.min' => 'Vui lòng chọn ít nhất một đề tài',
            'idCanBo.required' => 'Vui lòng chọn cán bộ phân công đọc duyệt',
        ];
    }

    protected function prepareForValidation(): void
    {
        $ids = $this->input('ids', []);
        if (!is_array($ids)) {
            $ids = [];
        }

        $this->merge([
            'ids' => array_values(array_map('intval', $ids)),
            'idCanBo' => (int) $this->input('idCanBo', 0),
        ]);
    }

    /** @return int[] */
    public function getIdsDeTai(): array
    {
        return $this->validated()['ids'];
    }

    public function getIdCanBo(): int
    {
        return (int) $this->validated()['idCanBo'];
    }
}
