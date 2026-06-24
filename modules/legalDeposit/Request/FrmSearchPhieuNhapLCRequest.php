<?php

namespace Modules\legalDeposit\Request;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\legalDeposit\Object\FilterPhieuNhapLC;

class FrmSearchPhieuNhapLCRequest extends FormRequest
{
    protected $casts = [
        "TuKhoa" => "string",
        "TuNgay" => "date",
        "DenNgay" => "date",
        "IsDeleted" => "bool",
        "relations" => "array",

    ];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            "TuKhoa" => "sometimes|string",
            "TuNgay" => "sometimes|date",
            "DenNgay" => "sometimes|date",
            "IsDeleted" => "sometimes|bool",
            "relations" => "sometimes|array",
            "relations.*" => "sometimes|string",
        ];
    }

    public function messages() {
        return [
            "TuKhoa.string" => config("label.INPUT_ERROR"),
            "TuNgay.date" => config("label.INPUT_ERROR"),
            "DenNgay.date" => config("label.INPUT_ERROR"),
            "IsDeleted.bool" => config("label.INPUT_ERROR"),
            "relations.array" => config("label.INPUT_ERROR"),
            "relations.*.string" => config("label.INPUT_ERROR"),
        ];
    }

    protected function prepareForValidation(): void
    {

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
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterPhieuNhapLC.
     *
     * @return FilterPhieuNhapLC
     */
    public function toFilter(): FilterPhieuNhapLC
    {
        $validated = $this->validated();
        $filter = new FilterPhieuNhapLC();

        foreach ($this->casts as $key => $type) {
            if (!array_key_exists($key, $validated)) {
                continue;
            }

            $normalizedValue = core_normalize_type_value($type, $validated[$key]);
            if ($type === "date") {
                $filter->{$key} = $normalizedValue ? new DateTime((string) $normalizedValue) : null;
                continue;
            }
            $filter->{$key} = $normalizedValue;
        }

        return $filter;
    }
}
