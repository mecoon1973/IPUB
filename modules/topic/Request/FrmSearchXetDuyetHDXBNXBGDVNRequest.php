<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\FilterXetDuyetHDXBNXBGDVN;

class FrmSearchXetDuyetHDXBNXBGDVNRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'TuNgay' => 'nullable|string',
            'DenNgay' => 'nullable|string',
            'ID_DonVi' => 'required|integer|min:1',
            'ids' => 'nullable|array',
            'ids.*' => 'integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'ID_DonVi.required' => 'Vui lòng chọn đơn vị tổ chức bản thảo',
            'ID_DonVi.min' => 'Vui lòng chọn đơn vị tổ chức bản thảo',
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
            'ID_DonVi' => (int) $this->input('ID_DonVi', 0),
        ]);
    }

    public function toFilter(): FilterXetDuyetHDXBNXBGDVN
    {
        $validated = $this->validated();

        return new FilterXetDuyetHDXBNXBGDVN([
            'TuNgay' => $validated['TuNgay'] ?? null,
            'DenNgay' => $validated['DenNgay'] ?? null,
            'ID_DonVi' => (int) $validated['ID_DonVi'],
            'idsDeTai' => $validated['ids'] ?? [],
        ]);
    }
}
