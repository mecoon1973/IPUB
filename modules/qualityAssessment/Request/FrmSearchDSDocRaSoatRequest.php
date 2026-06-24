<?php
namespace Modules\QualityAssessment\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\QualityAssessment\Object\FilterDSDocRaSoat;

class FrmSearchDSDocRaSoatRequest extends FormRequest
{
    protected $casts = [
        "Deleted" => "boolean",
        "IsSach" => "boolean",
        "Title" => "string",
        "Type" => "string",
        "TuNgay" => "date",
        "DenNgay" => "date",
        "relations" => "array",
    ];

    public function authorize()
    {
        return Auth::check();
    }

    public function rules() {
        return [
            "Deleted" => "sometimes|boolean",
            "IsSach" => "sometimes|boolean",
            "Title" => "sometimes|string|nullable",
            "Type" => "sometimes|string|nullable",
            "TuNgay" => "sometimes|date|nullable",
            "DenNgay" => "sometimes|date|nullable",
            "relations" => "sometimes|array",
        ];
    }

    public function messages() {
        return [
            "Deleted.boolean" => config("label.INPUT_ERROR"),
            "IsSach.boolean" => config("label.INPUT_ERROR"),
            "Title.string" => config("label.INPUT_ERROR"),
            "Type.string" => config("label.INPUT_ERROR"),
            "TuNgay.date" => config("label.INPUT_ERROR"),
            "DenNgay.date" => config("label.INPUT_ERROR"),
            "relations.array" => config("label.INPUT_ERROR"),
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];
        foreach ($this->casts as $field => $type) {
            if (! $this->has($field)) {
                continue;
            }
            $normalized[$field] = core_normalize_type_value($type, $this->input($field));
        }
        if (! empty($normalized)) {
            $this->merge($normalized);
        }
    }

    /**
     * @return FilterDSDocRaSoat
     */
    public function toFilter(): FilterDSDocRaSoat {
        $validated = $this->validated();
        $filter = new FilterDSDocRaSoat();

        foreach ($this->casts as $key => $type) {
            if (! array_key_exists($key, $validated)) {
                continue;
            }
            settype($validated[$key], $type);
            $filter->{$key} = $validated[$key];
        }

        return $filter;
    }
}
