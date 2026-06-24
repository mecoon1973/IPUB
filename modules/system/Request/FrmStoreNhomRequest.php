<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreNhomRequest extends FormRequest
{
    protected $casts = [
        'id' => 'int',
        'MaNhomNSD' => 'string',
        'TenNhomNSD' => 'string',
        'listIdQuyen' => 'array',
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
            'MaNhomNSD' => 'required|string|max:255',
            'TenNhomNSD' => 'required|string|max:2000',
            'listIdQuyen' => 'required|array',
            'listIdQuyen.*' => 'required|integer',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'MaNhomNSD.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'TenNhomNSD.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'id.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'listIdQuyen.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'listIdQuyen.*.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'listIdQuyen.*.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
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
            if($field === 'listIdQuyen') {
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
