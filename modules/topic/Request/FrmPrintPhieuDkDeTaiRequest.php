<?php
namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmPrintPhieuDkDeTaiRequest extends FormRequest

{
    protected $casts = [
        'template_name' => 'string',
        'template_format' => 'string',
        'path_file_docx' => 'string',
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
            'template_name' => 'required|string|max:255',
            'template_format' => 'required|string|in:pdf,docx,xlsx,html,txt',
            'path_file_docx' => 'nullable|string|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages() {
        return [
            'template_name.required' => 'Tên template là bắt buộc',
            'template_format.required' => 'Định dạng template là bắt buộc',
            'template_format.in' => 'Định dạng template không hợp lệ',
            'path_file_docx.string' => 'File docx gốc không hợp lệ',
            'path_file_docx.max' => 'File docx gốc không được vượt quá 255 ký tự',
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
