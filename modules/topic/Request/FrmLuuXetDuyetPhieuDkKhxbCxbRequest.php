<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\PhieuDkDetaiTrangThai;

class FrmLuuXetDuyetPhieuDkKhxbCxbRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $allowed = implode(',', PhieuDkDetaiTrangThai::cxbXetDuyetValues());

        return [
            'idPhieu' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.idDeTai' => 'required|integer|min:1',
            'items.*.TrangThai' => 'required|integer|in:' . $allowed,
        ];
    }

    public function messages(): array
    {
        return [
            'idPhieu.required' => 'Không xác định được phiếu trình CXB',
            'items.required' => 'Không có đề tài để lưu',
            'items.min' => 'Không có đề tài để lưu',
            'items.*.TrangThai.in' => 'Trạng thái xét duyệt không hợp lệ',
        ];
    }

  /** @return array<string, mixed> */
    public function toPayload(): array
    {
        $validated = $this->validated();

        return [
            'idPhieu' => (int) $validated['idPhieu'],
            'items' => $validated['items'],
        ];
    }
}
