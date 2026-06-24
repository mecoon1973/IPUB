<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreMangsachRequest extends FormRequest
{
    protected $casts = [
        'id' => 'int',
        'MaMang' => 'string',
        'TenMang' => 'string',
        'MoTa' => 'string',
        'KiHieu' => 'string',
        'ParentID' => 'int',
        'VAT' => 'int',
        'iOrder' => 'int',
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
            'MaMang' => 'required|string|max:255',
            'TenMang' => 'required|string|max:2000',
            'KiHieu' => 'required|string|max:255',
            'VAT' => 'required|integer',
            'iOrder' => 'required|integer',
            'MoTa' => 'sometimes|string|max:2000',
            'ParentID' => 'sometimes|integer',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'MaMang.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'TenMang.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'KiHieu.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'VAT.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'iOrder.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'id.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'ParentID.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'MoTa.string' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'MoTa.max' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
        ];
    }

    /**
     * Chuẩn hoá dữ liệu đầu vào.
     */
    protected function prepareForValidation(): void
    {

        $normalized = [];
        foreach ($this->casts
         as $field => $type) {
            if (!$this->has($field)) {
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
