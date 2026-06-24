<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterBienMoiTruong;

class FrmSearchBienMoiTruongRequest extends FormRequest
{
    protected $casts = [
        "ConfigSearch" => "string",
        "id_Dv" => "int",
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
            "ConfigSearch" => "sometimes|string",
            "id_Dv" => "sometimes|integer",
        ];
    }

    public function messages() {
        return [
            "ConfigSearch.string" => config("label.INPUT_ERROR"),
            "id_Dv.integer" => config("label.INPUT_ERROR"),
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];

        foreach ($this->casts as $field => $type) {
            $normalized[$field] = core_normalize_type_value($type, $this->input($field));
        }

        if (!empty($normalized)) {
            $this->merge($normalized);
        }
    }

    /**
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterHDXB.
     *
     * @return FilterBienMoiTruong
     */

    public function toFilter() : FilterBienMoiTruong {

        $validated = $this->validated();
        $filter = new FilterBienMoiTruong();

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
