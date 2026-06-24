<?php
namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterLoaiXbpLc;

class FrmSearchLoaiXbpLcRequest extends FormRequest
{
    protected $casts = [
        "IsDeleted" => "boolean",
        "InUsed" => "boolean",
        "TenLoai" => "string",
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
            "TenLoai" => "sometimes|string",
        ];
    }

    public function messages() {
        return [
            "IsDeleted.boolean" => config("label.INPUT_ERROR"),
            "InUsed.boolean" => config("label.INPUT_ERROR"),
            "TenLoai.string" => config("label.INPUT_ERROR"),
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
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterLoaiXbpLc.
     *
     * @return FilterLoaiXbpLc
     */
    public function toFilter() : FilterLoaiXbpLc {
        $validated = $this->validated();
        $filter = new FilterLoaiXbpLc();
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
