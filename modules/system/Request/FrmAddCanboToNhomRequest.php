<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmAddCanboToNhomRequest extends FormRequest
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
            "listIdUser" => "required|array",
            "listIdUser.*" => "required|integer",
        ];
    }

    public function messages() {
        return [
            "listIdUser.required" => config("label.INPUT_ERROR"),
            "listIdUser.array" => config("label.INPUT_ERROR"),
            "listIdUser.*.required" => config("label.INPUT_ERROR"),
            "listIdUser.*.integer" => config("label.INPUT_ERROR"),
        ];
    }

    protected function prepareForValidation(): void
    {
        $typeFields = ["listIdUser" => "array"];
        $normalized = [];

        foreach ($typeFields as $field => $type) {
            if (!$this->has($field)) {
                continue;
            }

            $value = $this->input($field);

            $normalized[$field] = core_normalize_type_value($type, $value);
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
        $validated = $this->validated();
        $validated['listIdUser'] = array_values(array_unique(array_map('intval', array_filter($validated['listIdUser'], fn ($v) => $v !== null && $v !== ''))));
        return $validated;
    }
}
