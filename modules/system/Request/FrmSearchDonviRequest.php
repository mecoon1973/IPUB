<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterDonvi;

class FrmSearchDonviRequest extends FormRequest
{
    protected $casts = [
        "BienTap" => "boolean",
        "IsDeleted" => "boolean",
        "NoiBo" => "boolean",
        "NhaIn" => "boolean",
        "LienKet" => "boolean",
    ];
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
            "BienTap" => "sometimes|boolean",
            "IsDeleted" => "sometimes|boolean",
            "NoiBo" => "sometimes|boolean",
            "NhaIn" => "sometimes|boolean",
            "LienKet" => "sometimes|boolean",
        ];
    }

    public function messages() {
        return [
            "BienTap.boolean" => config("label.INPUT_ERROR"),
            "IsDeleted.boolean" => config("label.INPUT_ERROR"),
            "NoiBo.boolean" => config("label.INPUT_ERROR"),
            "NhaIn.boolean" => config("label.INPUT_ERROR"),
            "LienKet.boolean" => config("label.INPUT_ERROR"),
        ];
    }

    public function toFilter() : FilterDonvi {

        $validated = $this->validated();
        $filter = new FilterDonvi();

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
