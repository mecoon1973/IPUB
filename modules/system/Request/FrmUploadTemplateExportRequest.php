<?php
namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\System\Object\TemplateExportFileField;

class FrmUploadTemplateExportRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'key' => 'required|string|max:255',
            'field' => ['required', 'string', Rule::in(TemplateExportFileField::all())],
            'file' => 'required|file|mimes:xlsx,xls,doc,docx|max:20480',
        ];
    }

    public function messages()
    {
        return [
            'key.required' => 'Vui lòng nhập key template trước khi tải file',
            'field.required' => 'Vui lòng chỉ định trường template cần cập nhật',
            'field.in' => 'Trường template không hợp lệ',
            'file.required' => 'Vui lòng chọn file template',
            'file.mimes' => 'Chỉ chấp nhận file (.xlsx, .xls, .doc, .docx)',
            'file.max' => 'File không được vượt quá 20MB',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $field = (string) $this->input('field');
            $file = $this->file('file');

            if (!$file instanceof UploadedFile || !in_array($field, TemplateExportFileField::all(), true)) {
                return;
            }

            $extension = strtolower($file->getClientOriginalExtension());

            if ($field === TemplateExportFileField::PATH_FILE_TEMPLATE
                && !in_array($extension, ['xlsx', 'xls'], true)) {
                $validator->errors()->add('file', 'Trường Excel chỉ chấp nhận file (.xlsx, .xls)');
            }

            if ($field === TemplateExportFileField::PATH_FILE_TEMPLATE_DOC
                && !in_array($extension, ['doc', 'docx'], true)) {
                $validator->errors()->add('file', 'Trường Word chỉ chấp nhận file (.doc, .docx)');
            }
        });
    }

    public function getTemplateKey(): string
    {
        return trim((string) $this->input('key'));
    }

    public function getTemplateFileField(): string
    {
        return (string) $this->input('field');
    }

    public function getUploadedFile(): UploadedFile
    {
        /** @var UploadedFile $file */
        $file = $this->file('file');
        return $file;
    }
}
