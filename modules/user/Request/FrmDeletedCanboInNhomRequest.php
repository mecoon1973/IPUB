<?php

namespace Modules\User\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\User\Object\FilterUser;

class FrmDeletedCanboInNhomRequest extends FormRequest
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
     * @return array
     */
    public function rules() {
        return [
            "idNhom" => "required|integer",
            "idCanbo" => "required|integer",
        ];
    }

    public function messages() {
        return [
            "idNhom.required" => config("label.INPUT_ERROR"),
            "idNhom.integer" => config("label.INPUT_ERROR"),
            "idCanbo.required" => config("label.INPUT_ERROR"),
            "idCanbo.integer" => config("label.INPUT_ERROR"),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            "idNhom" => (int)$this->input("idNhom"),
            "idCanbo" => (int)$this->input("idCanbo"),
        ]);
    }

    /**
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterHDXB.
     *
     * @return FilterUser
     */

    public function toPayload() : array {
        return [
            "idNhom" => $this->input("idNhom"),
            "idCanbo" => $this->input("idCanbo"),
        ];
    }
}
