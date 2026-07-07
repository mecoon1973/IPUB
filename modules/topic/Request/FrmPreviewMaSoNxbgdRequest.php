<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmPreviewMaSoNxbgdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
            'isMa12KiTu' => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => (int) $this->input('id', 0),
            'isMa12KiTu' => filter_var($this->input('isMa12KiTu', false), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
