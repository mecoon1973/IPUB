<?php

namespace Modules\User\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\User\Object\FilterUser;

class FrmSearchUserRequest extends FormRequest
{
    protected $casts = [
        "IsDeleted" => "boolean",
        "IsEditor" => "boolean",
        "IdNhom" => "int",
        "usernameSearch" => "string",
        "ID_DonVi" => "int",
        "_IdNhom" => "int",
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
            "IsDeleted" => "sometimes|boolean",
            "IsEditor" => "sometimes|boolean",
            "usernameSearch" => "sometimes|string",
            "IdNhom" => "sometimes|integer",
            "_IdNhom" => "sometimes|integer",
            "ID_DonVi" => "sometimes|integer",
            "relations" => "sometimes|array",
        ];
    }

    public function messages() {
        return [
            "IsDeleted.boolean" => config("label.INPUT_ERROR"),
            "IsEditor.boolean" => config("label.INPUT_ERROR"),
            "IdNhom.integer" => config("label.INPUT_ERROR"),
            "usernameSearch.string" => config("label.INPUT_ERROR"),
            "ID_DonVi.integer" => config("label.INPUT_ERROR"),
            "_IdNhom.integer" => config("label.INPUT_ERROR"),
            "relations.array" => config("label.INPUT_ERROR"),
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
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterHDXB.
     *
     * @return FilterUser
     */

    public function toFilter() : FilterUser {

        $validated = $this->validated();
        $filter = new FilterUser();
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
