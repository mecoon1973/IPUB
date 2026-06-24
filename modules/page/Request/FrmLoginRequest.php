<?php

namespace Modules\Page\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmLoginRequest extends FormRequest
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
            "username" => "required|string|max:255",
            "password" => "required|string|max:255",
        ];
    }

    public function messages() {
        return [
            "username.required" => config("label.INPUT_ERROR"),
            "password.required" => config("label.INPUT_ERROR"),
        ];
    }
}
