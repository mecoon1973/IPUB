<?php

namespace Modules\Page\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmForgetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            "email" => "required|string|email",
        ];
    }

    public function messages() {
        return [
            "email.required" => config("label.INPUT_ERROR"),
            "email.email" => config("label.EMAIL_INVALID"),
        ];
    }
}
