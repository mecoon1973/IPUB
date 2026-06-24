<?php

namespace Modules\User\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class FrmStoreUserRequest extends FormRequest
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
            '_id' => 'sometimes|integer|min:0',
            'MaCanBo' => 'sometimes|string|max:255',
            'HoTen' => 'required|string|max:500',
            'NgaySinh' => 'sometimes|nullable|date',
            'ID_ChucVu' => 'sometimes|integer|min:0',
            'ChucVuText' => 'sometimes|string|max:500',
            'ID_DonVi' => 'required|integer|min:1',
            'ID_ChuyenMon' => 'sometimes|integer|min:0|max:3',
            'SoDienThoai' => 'sometimes|string|max:255',
            'Email' => 'required|string|email|max:255',
            'DiaChi' => 'sometimes|string|max:2000',
            'UserName' => 'sometimes|string|max:255',
            'PassWord' => 'sometimes|string|max:255',
            'IsActive' => 'sometimes|boolean',
            'IsEditor' => 'sometimes|boolean',
            'UserThemes' => 'sometimes|string|max:255',
            'NgayHetHan' => 'sometimes|nullable|date',
            'SoLuongBanGhi' => 'sometimes|integer|min:0',
            'ID_Scale' => 'sometimes|integer|min:0',
            'NguoiKi' => 'sometimes|boolean',
            'InUsed' => 'sometimes|boolean',
            'IsDeleted' => 'sometimes|boolean',
            'DaGui' => 'sometimes|boolean',
            'KhoaGuiNhan' => 'sometimes|string|max:255',
            'MaSoChungChi' => 'sometimes|string|max:255',
            'NgayCap' => 'sometimes|nullable|date',
            'NoiCap' => 'sometimes|string|max:500',
            'ChucDanhBienTap' => 'sometimes|string|max:2000',
            'isSpecial' => 'sometimes|boolean',
            'KyQDXB' => 'sometimes|boolean',
            'UQKyQDXB' => 'sometimes|boolean',
            'NguoiSoanThao' => 'sometimes|boolean',
            'KyNhayQDXB' => 'sometimes|boolean',
            'ID_VSSIGN' => 'sometimes|string|max:255',
            'SignatureUrl_VSSIGN' => 'sometimes|string|max:2000',
            'isActive_VSSIGN' => 'sometimes|boolean',
            'nhom_ids' => 'sometimes|array',
            'quyen_ids' => 'sometimes|array',
            'nhom_ids.*' => 'sometimes|integer',
            'quyen_ids.*' => 'sometimes|integer',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'HoTen.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'Email.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'Email.email' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'ID_DonVi.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'ID_DonVi.min' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'nhom_ids.array' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'quyen_ids.array' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'nhom_ids.*.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'quyen_ids.*.integer' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('id')) {
            $this->merge(['_id' => (int) $this->input('id')]);
        }

        if ($this->has('password')) {
            $this->merge(['PassWord' => $this->input('password')]);
        }
        $typeFields = [
            '_id' => 'int',
            'MaCanBo' => 'string',
            'HoTen' => 'string',
            'ID_ChucVu' => 'int',
            'ChucVuText' => 'string',
            'ID_DonVi' => 'int',
            'ID_ChuyenMon' => 'int',
            'SoDienThoai' => 'string',
            'Email' => 'string',
            'DiaChi' => 'string',
            'UserName' => 'string',
            'PassWord' => 'string',
            'IsActive' => 'boolean',
            'IsEditor' => 'boolean',
            'UserThemes' => 'string',
            'SoLuongBanGhi' => 'int',
            'ID_Scale' => 'int',
            'NguoiKi' => 'boolean',
            'InUsed' => 'boolean',
            'IsDeleted' => 'boolean',
            'DaGui' => 'boolean',
            'KhoaGuiNhan' => 'string',
            'MaSoChungChi' => 'string',
            'NoiCap' => 'string',
            'ChucDanhBienTap' => 'string',
            'isSpecial' => 'boolean',
            'KyQDXB' => 'boolean',
            'UQKyQDXB' => 'boolean',
            'NguoiSoanThao' => 'boolean',
            'KyNhayQDXB' => 'boolean',
            'ID_VSSIGN' => 'string',
            'SignatureUrl_VSSIGN' => 'string',
            'isActive_VSSIGN' => 'boolean',
            'NgaySinh' => 'datetime',
            'NgayCap' => 'datetime',
            'NgayHetHan' => 'datetime',
            'nhom_ids' => 'array',
            'quyen_ids' => 'array',
        ];
        $normalized = [];

        foreach ($typeFields as $field => $type) {
            if (! $this->has($field)) {
                continue;
            }
            if($field === 'nhom_ids' || $field === 'quyen_ids') {
                $normalized[$field] = array_map('intval', $this->input($field));
            } else {
                $normalized[$field] = core_normalize_type_value($type, $this->input($field));
            }
        }

        if (! empty($normalized)) {
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
