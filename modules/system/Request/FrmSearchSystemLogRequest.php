<?php

namespace Modules\System\Request;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterSystemLog;

class FrmSearchSystemLogRequest extends FormRequest
{
    protected $casts = [
        "accountName" => "string",
        "userName" => "string",
        "content" => "string",
        "id_Dv" => "int",
        "startDate" => "date",
        "endDate" => "date",
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
            "accountName" => "sometimes|string",
            "userName" => "sometimes|string",
            "content" => "sometimes|string",
            "id_Dv" => "sometimes|integer",
            "startDate" => "sometimes|date|nullable",
            "endDate" => "sometimes|date|nullable",
        ];
    }

    public function messages() {
        return [
            "accountName.string" => config("label.INPUT_ERROR"),
            "userName.string" => config("label.INPUT_ERROR"),
            "content.string" => config("label.INPUT_ERROR"),
            "id_Dv.integer" => config("label.INPUT_ERROR"),
            "startDate.date" => config("label.INPUT_ERROR"),
            "endDate.date" => config("label.INPUT_ERROR"),
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
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterSystemLog.
     *
     * @return FilterSystemLog
     */

    public function toFilter() : FilterSystemLog {

        $validated = $this->validated();
        $filter = new FilterSystemLog();

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
