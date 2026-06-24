<?php

namespace Modules\Topic\Request;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\FilterQDIn;

class FrmSearchQDInRequest extends FormRequest
{
    protected $casts = [
        "ID_DV_QD" => "integer",
        "startDate" => "date",
        "endDate" => "date",
        "SoQD" => "string",
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
            "ID_DV_QD" => "sometimes|integer",
            "startDate" => "sometimes|date|nullable",
            "endDate" => "sometimes|date|nullable",
            "SoQD" => "sometimes|string",
        ];
    }

    public function messages() {
        return [
            "ID_DV_QD.integer" => config("label.INPUT_ERROR"),
            "SoQD.string" => config("label.INPUT_ERROR"),
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
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterQDIn.
     *
     * @return FilterQDIn
     */

    public function toFilter() : FilterQDIn {

        $validated = $this->validated();
        $filter = new FilterQDIn();

        foreach ($this->casts as $key => $type) {
            if (!array_key_exists($key, $validated)) {
                continue;
            }

            $normalizedValue = core_normalize_type_value($type, $validated[$key]);
            if ($type === "date") {
                $filter->{$key} = $normalizedValue ? new DateTime((string)$normalizedValue) : null;
                continue;
            }
            $filter->{$key} = $normalizedValue;
        }

        return $filter;

    }
}
