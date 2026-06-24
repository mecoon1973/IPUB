<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\FilterCT_Detai_Congdoan;

class FrmSearchDetaiCongDoanRequest extends FormRequest
{
    protected $casts = [
        "IDDeTai" => "integer",
        "IDSach" => "integer",
        "MaCD" => "string",
        "relations" => "array",
        "limit" => "integer",
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
            "IDDeTai" => "sometimes|integer",
            "IDSach" => "sometimes|integer",
            "MaCD" => "sometimes|string",
            "relations" => "sometimes|array",
            "relations.*" => "string",
            "limit" => "sometimes|integer",
        ];
    }

    public function messages() {
        return [
            "IDDeTai.integer" => config("label.INPUT_ERROR"),
            "IDSach.integer" => config("label.INPUT_ERROR"),
            "MaCD.string" => config("label.INPUT_ERROR"),
            "relations.array" => config("label.INPUT_ERROR"),
            "relations.*.string" => config("label.INPUT_ERROR"),
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

            $value = $this->input($field);

            $normalized[$field] = core_normalize_type_value($type, $value);
        }

        if (!empty($normalized)) {
            $this->merge($normalized);
        }
    }

    /**
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterCT_Detai_Congdoan.
     *
     * @return FilterCT_Detai_Congdoan
     */

    public function toFilter() : FilterCT_Detai_Congdoan {

        $validated = $this->validated();
        $filter = new FilterCT_Detai_Congdoan();
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
