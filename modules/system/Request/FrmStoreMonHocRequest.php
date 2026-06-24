<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreMonHocRequest extends FormRequest
{
    protected $casts = [
        'id' => 'int',
        'MaMonHoc' => 'string',
        'TenMonHoc' => 'string',
        'MoTa' => 'string',
        'KiHieu' => 'string',
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
            'MaMonHoc' => 'required|string|max:255',
            'TenMonHoc' => 'required|string|max:2000',
            'MoTa' => 'required|string|max:2000',
            'KiHieu' => 'required|string|max:255',

        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'MaMonHoc.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'TenMonHoc.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'MoTa.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'KiHieu.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'id.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
        ];
    }

    /**
     * Chuẩn hoá dữ liệu đầu vào.
     */
    protected function prepareForValidation(): void
    {

        $normalized = [];
        foreach ($this->casts as $field => $type) {
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
