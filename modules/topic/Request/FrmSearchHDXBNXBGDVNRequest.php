<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\FilterHDXBNXBGDVN;

class FrmSearchHDXBNXBGDVNRequest extends FormRequest
{
    protected $casts = [
        "TenDeTai" => "string",
        "ID_DonVi" => "integer",
        "PhanCong" => "integer",
        "TrangThai" => "integer",
        "limit" => "integer",
    ];

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            "TenDeTai" => "nullable|string",
            "ID_DonVi" => "nullable|integer",
            "PhanCong" => "nullable|integer|in:" . implode(',', [
                FilterHDXBNXBGDVN::PHAN_CONG_TAT_CA,
                FilterHDXBNXBGDVN::PHAN_CONG_CHUA,
                FilterHDXBNXBGDVN::PHAN_CONG_DA_TAT_CA,
                FilterHDXBNXBGDVN::PHAN_CONG_DA_CA_NHAN,
            ]),
            "TrangThai" => "nullable|integer",
            "limit" => "nullable|integer",
        ];
    }

    public function messages(): array
    {
        return [
            "ID_DonVi.integer" => config("label.INPUT_ERROR"),
            "PhanCong.integer" => config("label.INPUT_ERROR"),
            "TrangThai.integer" => config("label.INPUT_ERROR"),
            "limit.integer" => config("label.INPUT_ERROR"),
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

    public function toFilter(): FilterHDXBNXBGDVN
    {
        $validated = $this->validated();
        $filter = new FilterHDXBNXBGDVN();

        foreach ($this->casts as $key => $type) {
            if (!array_key_exists($key, $validated)) {
                continue;
            }
            settype($validated[$key], $type);
            $filter->{$key} = $validated[$key];
        }

        return $filter;
    }
}
