<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\NxCanBoDetaiDuyet;

class FrmLuuDocDuyetHDXBNXBGDVNRequest extends FormRequest
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
            'items.*.YKienNhanXet' => 'nullable|string',
            'items.*.ThongTinLienQuan' => 'nullable|string',
            'items.*.Duyet' => 'required|integer|in:' . implode(',', [
                NxCanBoDetaiDuyet::CHUA_XET,
                NxCanBoDetaiDuyet::DUYET,
                NxCanBoDetaiDuyet::TRA_LAI,
            ]),
        ];
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
