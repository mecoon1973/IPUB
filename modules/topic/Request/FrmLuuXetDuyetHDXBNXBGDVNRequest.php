<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\NxCanBoDetaiDuyet;

class FrmLuuXetDuyetHDXBNXBGDVNRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.idDeTai' => 'required|integer|min:1',
            'items.*.idNxCanBoDetai' => 'required|integer|min:1',
            'items.*.YKienDocDuyet' => 'nullable|string',
            'items.*.YKienHDXB' => 'nullable|string',
            'items.*.Duyet' => 'required|integer|in:' . implode(',', [
                NxCanBoDetaiDuyet::CHUA_XET,
                NxCanBoDetaiDuyet::DUYET,
                NxCanBoDetaiDuyet::TRA_LAI,
            ]),
            'items.*.YeuCauDocKiemDinh' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $items = $this->input('items', []);
        if (!is_array($items)) {
            return;
        }

        foreach ($items as $index => $item) {
            if (!is_array($item) || !array_key_exists('YeuCauDocKiemDinh', $item)) {
                continue;
            }

            $items[$index]['YeuCauDocKiemDinh'] = filter_var(
                $item['YeuCauDocKiemDinh'],
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            ) ?? false;
        }

        $this->merge(['items' => $items]);
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Không có đề tài để lưu',
            'items.min' => 'Không có đề tài để lưu',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function getItems(): array
    {
        return $this->validated()['items'];
    }
}
