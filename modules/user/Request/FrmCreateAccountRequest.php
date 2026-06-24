<?php

namespace Modules\User\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class FrmCreateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Khớp form {@see UserFormFields} + các field mặc định trong state (Partial User).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            "UserName" => "required|string|max:255",
            "PassWord" => "required|string|max:255",
            "ConfirmPassWord" => "required|string|max:255",
            "ID_DonVi" => "required|integer",
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'UserName.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'PassWord.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'ConfirmPassWord.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'ID_DonVi.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('PassWord')) {
            $this->merge(['PassWord' => $this->input('PassWord')]);
        }

        if ($this->has('ConfirmPassWord')) {
            $this->merge(['ConfirmPassWord' => $this->input('ConfirmPassWord')]);
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
