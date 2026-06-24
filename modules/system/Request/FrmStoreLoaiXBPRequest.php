<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreLoaiXBPRequest extends FormRequest
{
    protected $casts = [
        'id' => 'int',
        'TenLoai' => 'string',
        'MaLoai' => 'string',
        'MoTa' => 'string',
        'KiHieu' => 'string',
        'Type' => 'int',
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
        return [
            'id' => 'sometimes|integer',
            'TenLoai' => 'required|string',
            'MaLoai' => 'required|string',
            'KiHieu' => 'required|string',
            'Type' => 'required|integer',
            'MoTa' => 'sometimes|string',

        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'MaLoai.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'TenLoai.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'KiHieu.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'Type.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'MoTa.string' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'id.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
        ];
    }

    /**
     * Chuẩn hoá dữ liệu đầu vào.
     */
    protected function prepareForValidation(): void
    {

        $normalized = [];
        foreach ($this->casts
         as $field => $type) {
            if (!$this->has($field)) {
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
