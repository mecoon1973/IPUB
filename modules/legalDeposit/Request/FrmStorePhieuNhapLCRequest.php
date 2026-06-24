<?php

namespace Modules\legalDeposit\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStorePhieuNhapLCRequest extends FormRequest
{

    protected $casts = [

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


        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [

        ];
    }

    protected function prepareForValidation(): void
    {

        // Một số cột trong DB có thể là số nhưng phía client gửi lên (hoặc server trả về) dạng number,
        // trong khi rule đang yêu cầu string -> cần chuẩn hoá number/bool thành string.

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
