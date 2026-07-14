<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmCapMaSoNxbgdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
            'maSo' => 'required|string|min:1|max:20',
            'isMa12KiTu' => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => (int) $this->input('id', 0),
            'maSo' => trim((string) $this->input('maSo', '')),
            'isMa12KiTu' => filter_var($this->input('isMa12KiTu', false), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function messages(): array
    {
        return [
            'maSo.required' => 'Vui lòng nhập mã số muốn cấp',
        ];
    }
}
