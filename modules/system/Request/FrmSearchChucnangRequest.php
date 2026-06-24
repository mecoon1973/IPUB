<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterChucnang;

class FrmSearchChucnangRequest extends FormRequest
{
    protected $casts = [
        "Deleted" => "boolean",
    ];
    protected function prepareForValidation(): void
    {
        $typeFields = [
            "Deleted" => "boolean",
        ];
        $normalized = [];

        foreach ($typeFields as $field => $type) {
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
            "Deleted" => "sometimes|boolean",
        ];
    }

    public function messages() {
        return [
            "Deleted.boolean" => config("label.INPUT_ERROR"),
        ];
    }

    public function toFilter() : FilterChucnang {

        $validated = $this->validated();
        $filter = new FilterChucnang();

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
