<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreDetaiCongDoanRequest extends FormRequest
{

    protected $casts = [
        'IDDeTai' => 'integer',
        'IDCongDoan' => 'integer',
        'IDSach' => 'integer',
        'MaCD' => 'string',
        'GhiChu' => 'string',
        'NewValue' => 'string',
        'NoiDung' => 'string',
        'OldValue' => 'string',
    ];

    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Khớp $fillable của DM_DONVI; chỉ MaDonVi + TenDonVi bắt buộc, còn lại có thể không gửi.
        return [
            'id' => 'sometimes|integer',
            'IDDeTai' => 'required|integer',
            'IDCongDoan' => 'required|integer',
            'IDSach' => 'sometimes|integer',
            'MaCD' => 'required|string|max:255',
            'GhiChu' => 'sometimes|string|max:255',
            'NewValue' => 'sometimes|string|max:255',
            'NoiDung' => 'required|string|max:255',
            'OldValue' => 'sometimes|string|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'IDDeTai.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'IDCongDoan.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'IDSach.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'MaCD.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'GhiChu.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'NewValue.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'NoiDung.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'OldValue.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];
        foreach ($this->casts as $field => $type) {
            if (! $this->has($field)) {
                continue;
            }
            $normalized[$field] = core_normalize_type_value($type, $this->input($field));
        }
        if (!empty($normalized)) {
            $this->merge($normalized);
        }

    }

    /**
     * Dữ liệu an toàn để ghi DB (chỉ field đã rule).
     *
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return $this->validated();
    }
}
