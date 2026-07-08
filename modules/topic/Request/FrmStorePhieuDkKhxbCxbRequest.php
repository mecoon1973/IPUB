<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStorePhieuDkKhxbCxbRequest extends FormRequest
{
    protected $casts = [
        'id' => 'integer',
        'MaSo' => 'string',
        'TieuDe' => 'string',
        'NoiDung' => 'string',
        'NoiNhan2' => 'string',
        'NgayDK' => 'datetime',
        'ID_NguoiKi' => 'integer',
        'KiThay' => 'boolean',
        'listIdDeTai' => 'array',
    ];

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'id' => 'sometimes|integer',
            'MaSo' => 'sometimes|string',
            'TieuDe' => 'required|string',
            'NoiDung' => 'required|string',
            'NoiNhan2' => 'sometimes|string|nullable',
            'NgayDK' => 'sometimes|date|nullable',
            'ID_NguoiKi' => 'sometimes|integer|nullable',
            'KiThay' => 'sometimes|boolean',
            'listIdDeTai' => 'sometimes|array',
            'listIdDeTai.*' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'TieuDe.required' => 'Vui lòng nhập tiêu đề',
            'NoiDung.required' => 'Vui lòng nhập nội dung',
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];

        foreach ($this->casts as $field => $type) {
            if (!$this->has($field)) {
                continue;
            }

            if ($field === 'listIdDeTai') {
                $normalized[$field] = $this->input($field);
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
