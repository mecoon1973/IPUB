<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterMonhoc;

class FrmSearchMonhocRequest extends FormRequest
{
    protected $casts = [
        "IsDeleted" => "boolean",
        "IsUsed" => "boolean",
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
            "IsUsed" => "sometimes|boolean",
        ];
    }

    public function messages() {
        return [
            "IsDeleted.boolean" => config("label.INPUT_ERROR"),
            "IsUsed.boolean" => config("label.INPUT_ERROR"),
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
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterMonhoc.
     *
     * @return FilterMonhoc
     */

    public function toFilter() : FilterMonhoc {

        $validated = $this->validated();
        $filter = new FilterMonhoc();

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
