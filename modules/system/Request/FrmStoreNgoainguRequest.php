<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreNgoainguRequest extends FormRequest
{
    protected $casts = [
        'id' => 'int',
        'MaNgoaiNgu' => 'string',
        'TenNgoaiNgu' => 'string',
        'ThuTu' => 'int',
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
            'MaNgoaiNgu' => 'required|string',
            'TenNgoaiNgu' => 'required|string',
            'ThuTu' => 'required|int',

        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'MaNgoaiNgu.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'TenNgoaiNgu.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'ThuTu.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'id.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
        ];
    }

    /**
     * Chuẩn hoá dữ liệu đầu vào.
     */
    protected function prepareForValidation(): void
    {

        $normalized = [];
        foreach ($this->casts as $field => $type) {
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
