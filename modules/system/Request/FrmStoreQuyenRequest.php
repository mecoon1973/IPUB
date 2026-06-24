<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreQuyenRequest extends FormRequest
{

    protected $casts = [
        'id' => 'int',
        'MaQuyen' => 'string',
        'TenQuyen' => 'string',
        'ThuTu' => 'int',
        'IsDeleted' => 'boolean',
        'InUsed' => 'boolean',
        'ParentID' => 'int',
        'listIdFunctions' => 'array',
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
        // Khớp $fillable của DM_DONVI; chỉ MaDonVi + TenDonVi bắt buộc, còn lại có thể không gửi.
        return [
            'id' => 'sometimes|integer',
            'MaQuyen' => 'required|string|max:255',
            'TenQuyen' => 'required|string|max:2000',
            'ThuTu' => 'sometimes|integer',
            'IsDeleted' => 'sometimes|boolean',
            'InUsed' => 'sometimes|boolean',
            'ParentID' => 'sometimes|integer',
            'listIdFunctions' => 'sometimes|array',
            'listIdFunctions.*' => 'sometimes|integer',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'MaQuyen.required' => config("label.INPUT_ERROR"),
            'TenQuyen.required' => config("label.INPUT_ERROR"),
            'ThuTu.integer' => config("label.INPUT_ERROR"),
            'IsDeleted.boolean' => config("label.INPUT_ERROR"),
            'InUsed.boolean' => config("label.INPUT_ERROR"),
            'ParentID.integer' => config("label.INPUT_ERROR"),
            'listIdFunctions.array' => config("label.INPUT_ERROR"),
        ];
    }

    /**
     * Chuẩn hoá dữ liệu đầu vào.
     */
    protected function prepareForValidation(): void
    {
        $normalized = [];
        foreach ($this->casts as $field => $type) {
            if (! $this->has($field)) {
                continue;
            }
            if($field === 'listIdFunctions') {
                $normalized[$field] = array_map('intval', $this->input($field));
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
