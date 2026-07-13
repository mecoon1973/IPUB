<?php
namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterTemplateExport;

class FrmSearchTemplateExportRequest extends FormRequest
{
    protected $casts = [
        'id' => 'int',
        'key' => 'string',
        'name' => 'string',
        'path_file_template' => 'string',
    ];

    public function authorize()
    {
        return Auth::check();
    }

    public function rules() {
        return [
            'key' => 'sometimes|string',
            'name' => 'sometimes|string',
            'path_file_template' => 'sometimes|string',
        ];
    }

    public function messages() {
        return [
            'key.string' => 'Key phải là chuỗi',
            'name.string' => 'Tên phải là chuỗi',
            'path_file_template.string' => 'Đường dẫn file template phải là chuỗi',
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

    public function toFilter() : FilterTemplateExport {
        $validated = $this->validated();
        $filter = new FilterTemplateExport();
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
