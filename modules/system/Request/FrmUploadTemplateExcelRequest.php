<?php
namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class FrmUploadTemplateExcelRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'key' => 'required|string|max:255',
            'file' => 'required|file|mimes:xlsx,xls|max:20480',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'key.required' => 'Vui lòng nhập key template trước khi tải file',
            'file.required' => 'Vui lòng chọn file template',
            'file.mimes' => 'Chỉ chấp nhận file Excel (.xlsx, .xls)',
            'file.max' => 'File không được vượt quá 20MB',
        ];
    }

    public function getTemplateKey(): string
    {
        return trim((string) $this->input('key'));
    }

    public function getUploadedFile(): UploadedFile
    {
        /** @var UploadedFile $file */
        $file = $this->file('file');
        return $file;
    }
}
