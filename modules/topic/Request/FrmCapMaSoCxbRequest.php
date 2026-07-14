<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmCapMaSoCxbRequest extends FormRequest
{
    protected $casts = [
        'idPhieu' => 'integer',
        'SoCvCxb' => 'string',
        'SoCvNxbgd' => 'string',
        'NgayCap' => 'datetime',
        'NamCap' => 'string',
        'MaSoCxb' => 'string',
    ];

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'idPhieu' => 'required|integer',
            'SoCvCxb' => 'required|string',
            'SoCvNxbgd' => 'required|string',
            'NgayCap' => 'required|date',
            'NamCap' => 'sometimes|string|nullable',
            'MaSoCxb' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'idPhieu.required' => 'Không xác định được phiếu trình CXB',
            'SoCvCxb.required' => 'Vui lòng nhập số công văn xác nhận của CXB',
            'SoCvNxbgd.required' => 'Vui lòng nhập số công văn của NXBGDVN',
            'NgayCap.required' => 'Vui lòng nhập ngày cấp',
            'MaSoCxb.required' => 'Vui lòng nhập mã số CXB',
        ];
    }

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

    public function toPayload(): array
    {
        return $this->validated();
    }
}
