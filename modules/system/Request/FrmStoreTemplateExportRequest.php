<?php
namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\System\Object\ContentEditTemplate;

class FrmStoreTemplateExportRequest extends FormRequest

{
    protected $casts = [
        'id' => 'int',
        'key' => 'string',
        'name' => 'string',
        'path_file_template' => 'string',
        'path_file_template_doc' => 'string',
        'content_edit' => 'array',
    ];

    public function authorize()
    {
        return Auth::check();
    }

    public function rules() {
        return [
            'id' => 'sometimes|integer',
            'key' => 'required|string',
            'name' => 'required|string',
            'path_file_template' => 'sometimes|string',
            'path_file_template_doc' => 'sometimes|string',
            'content_edit' => 'sometimes|array',
            'content_edit.*.type' => ['sometimes', 'string', Rule::in(ContentEditTemplate::TYPE_TEXT, ContentEditTemplate::TYPE_LOOP)],
            'content_edit.*.key_data' => 'sometimes|string',
            'content_edit.*.map_replate' => 'sometimes|array',
            'content_edit.*.map_replate.*.value' => 'sometimes|string',
            'content_edit.*.map_replate.*.callback' => 'sometimes|nullable|string',
        ];
    }

    public function messages() {
        return [
            'id.integer' => 'ID phải là số nguyên',
            'key.required' => 'Key là bắt buộc',
            'key.string' => 'Key phải là chuỗi',
            'name.required' => 'Tên là bắt buộc',
            'name.string' => 'Tên phải là chuỗi',
            'path_file_template.string' => 'Đường dẫn file template phải là chuỗi',
            'path_file_template_doc.string' => 'Đường dẫn file template Word phải là chuỗi',
            'content_edit.array' => 'Cấu hình nội dung phải là mảng',
            'content_edit.*.type.in' => 'Loại chèn nội dung phải là text hoặc loop',
            'content_edit.*.key_data.string' => 'key_data phải là chuỗi',
            'content_edit.*.map_replate.array' => 'map_replate phải là mảng',
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

    public function toPayload(): array
    {
        return $this->validated();
    }
}
