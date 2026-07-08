<?php
namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\System\Object\ContentEditTemplate;

class FrmStoreTemplateExcelRequest extends FormRequest

{
    protected $casts = [
        'id' => 'int',
        'key' => 'string',
        'name' => 'string',
        'path_file_template' => 'string',
        'content_edit' => 'array',
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
            'id' => 'sometimes|integer',
            'key' => 'required|string',
            'name' => 'required|string',
            'path_file_template' => 'sometimes|string',
            'content_edit' => 'sometimes|array',
            'content_edit.*.type' => ['sometimes', 'string', Rule::in(ContentEditTemplate::TYPE_TEXT, ContentEditTemplate::TYPE_LOOP)],
            'content_edit.*.key_data' => 'sometimes|string',
            'content_edit.*.map_replate' => 'sometimes|array',
            'content_edit.*.map_replate.*.value' => 'sometimes|string',
            'content_edit.*.map_replate.*.callback' => 'sometimes|nullable|string',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages() {
        return [
            'id.integer' => 'ID phải là số nguyên',
            'key.required' => 'Key là bắt buộc',
            'key.string' => 'Key phải là chuỗi',
            'name.required' => 'Tên là bắt buộc',
            'name.string' => 'Tên phải là chuỗi',
            'path_file_template.string' => 'Đường dẫn file template phải là chuỗi',
            'content_edit.array' => 'Cấu hình nội dung phải là mảng',
            'content_edit.*.type.in' => 'Loại chèn nội dung phải là text hoặc loop',
            'content_edit.*.key_data.string' => 'key_data phải là chuỗi',
            'content_edit.*.map_replate.array' => 'map_replate phải là mảng',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Một số cột trong DB có thể là số nhưng phía client gửi lên (hoặc server trả về) dạng number,
        // trong khi rule đang yêu cầu string -> cần chuẩn hoá number/bool thành string.
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
     * Dữ liệu an toàn để ghi DB (chỉ field đã rule).
     *
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return $this->validated();
    }
}
