<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreChucnangRequest extends FormRequest
{

    protected $casts = [
        'id' => 'int',
        'Code' => 'string',
        'Title' => 'string',
        'FunctionCode' => 'string',
        'Href' => 'string',
        'ChildFunctionCode' => 'string',
        'isLinkFull' => 'boolean',
        'Target' => 'string',
        'Description' => 'string',
        'OnMenu' => 'boolean',
        'ThuTu' => 'integer',
        'ParentID' => 'integer',
        'PhanHeID' => 'integer',
        'Crumb' => 'string',
    ];

    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {

        return [
            'id' => 'sometimes|integer',
            'Code' => 'required|string',
            'Title' => 'required|string',
            'FunctionCode' => 'required|string',
            'Href' => 'sometimes|string',
            'ChildFunctionCode' => 'sometimes|string',
            'isLinkFull' => 'sometimes|boolean',
            'Target' => 'sometimes|string',
            'Description' => 'sometimes|string',
            'OnMenu' => 'sometimes|boolean',
            'ThuTu' => 'sometimes|integer',
            'ParentID' => 'sometimes|integer',
            'PhanHeID' => 'sometimes|integer',
            'Crumb' => 'sometimes|string',

        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'Code.required' => config("label.INPUT_ERROR"),
            'Title.required' => config("label.INPUT_ERROR"),
            'FunctionCode.required' => config("label.INPUT_ERROR"),
            'Href.string' => config("label.INPUT_ERROR"),
            'ChildFunctionCode.string' => config("label.INPUT_ERROR"),
            'isLinkFull.boolean' => config("label.INPUT_ERROR"),
            'Target.string' => config("label.INPUT_ERROR"),
            'Description.string' => config("label.INPUT_ERROR"),
            'OnMenu.boolean' => config("label.INPUT_ERROR"),
            'ThuTu.integer' => config("label.INPUT_ERROR"),
            'ParentID.integer' => config("label.INPUT_ERROR"),
            'PhanHeID.integer' => config("label.INPUT_ERROR"),
            'Crumb.string' => config("label.INPUT_ERROR"),
            'id.integer' => config("label.INPUT_ERROR"),
        ];
    }

    protected function prepareForValidation(): void
    {

        // Một số cột trong DB có thể là số nhưng phía client gửi lên (hoặc server trả về) dạng number,
        // trong khi rule đang yêu cầu string -> cần chuẩn hoá number/bool thành string.

        $normalized = [];
        foreach ($this->casts as $field => $type) {
            if (! $this->has($field)) {
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
