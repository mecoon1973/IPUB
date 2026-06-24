<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterQuyen;

class FrmSearchQuyenRequest extends FormRequest
{
    protected $casts = [
        "IsDeleted" => "boolean",
        "InUsed" => "boolean",
        "ParentID" => "int",
        "MaQuyen" => "string",
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
            "InUsed" => "sometimes|boolean",
            "ParentID" => "sometimes|integer",
            "MaQuyen" => "sometimes|string",
        ];
    }

    public function messages() {
        return [
            "IsDeleted.boolean" => config("label.INPUT_ERROR"),
            "InUsed.boolean" => config("label.INPUT_ERROR"),
            "ParentID.integer" => config("label.INPUT_ERROR"),
            "MaQuyen.string" => config("label.INPUT_ERROR"),
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

    /**
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterQuyen.
     *
     * @return FilterQuyen
     */

    public function toFilter() : FilterQuyen {

        $validated = $this->validated();
        $filter = new FilterQuyen();

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
