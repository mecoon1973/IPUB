<?php

namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreDonviRequest extends FormRequest
{
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
            'MaDonVi' => 'required|string|max:255',
            'TenDonVi' => 'required|string|max:2000',
            'DiaChi' => 'sometimes|string|max:2000',
            'Website' => 'sometimes|string|max:500',
            'Email' => 'sometimes|string|email|max:255',
            'SoDienThoai' => 'sometimes|string|max:255',
            'SoFax' => 'sometimes|string|max:255',
            'NhaIn' => 'sometimes|boolean',
            'DauThau' => 'sometimes|boolean',
            'BienTap' => 'sometimes|boolean',
            'LienKet' => 'sometimes|boolean',
            'NoiBo' => 'sometimes|boolean',
            'MST' => 'sometimes|string|max:255',
            'SoTaiKhoan' => 'sometimes|string|max:255',
            'TaiNganHang' => 'sometimes|string|max:255',
            'MaSoPhu' => 'sometimes|string|max:255',
            'ParentID' => 'sometimes|integer',
            'ThuTu' => 'sometimes|integer',
            'ID_Childs' => 'sometimes|string|max:2000',
            'Active' => 'sometimes|boolean',
            'InUsed' => 'sometimes|boolean',
            'IsDeleted' => 'sometimes|boolean',
            'DaGui' => 'sometimes|boolean',
            'KhoaGuiNhan' => 'sometimes|string|max:255',
            'TinhThanh' => 'sometimes|string|max:255',
            'MaTinh' => 'sometimes|string|max:255',
            'LicenseKey' => 'sometimes|string|max:500',
            'NgayTTPQLXB' => 'sometimes|integer',
            'IsCreateQDXB' => 'sometimes|boolean',
            'KiHieuMoi' => 'required|string|max:255',
            'KiHieuTaiBan' => 'required|string|max:255',
            'KiHieuPhu' => 'sometimes|string|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'MaDonVi.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'TenDonVi.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'KiHieuMoi.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
            'KiHieuTaiBan.required' => config('label.INPUT_ERROR', 'Dữ liệu không hợp lệ'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('ParentID')) {
            $this->merge(['ParentID' => 0]);
        }

        // Một số cột trong DB có thể là số nhưng phía client gửi lên (hoặc server trả về) dạng number,
        // trong khi rule đang yêu cầu string -> cần chuẩn hoá number/bool thành string.
        $typeFields = [
            'MaDonVi' => 'string',
            'TenDonVi' => 'string',
            'DiaChi' => 'string',
            'Website' => 'string',
            'SoDienThoai' => 'string',
            'SoFax' => 'string',
            'MST' => 'string',
            'SoTaiKhoan' => 'string',
            'TaiNganHang' => 'string',
            'MaSoPhu' => 'string',
            'ID_Childs' => 'string',
            'KhoaGuiNhan' => 'string',
            'TinhThanh' => 'string',
            'MaTinh' => 'string',
            'LicenseKey' => 'string',
            'KiHieuMoi' => 'string',
            'KiHieuTaiBan' => 'string',
            'KiHieuPhu' => 'string',
            'ParentID' => 'int',
            'ThuTu' => 'int',
            'NgayTTPQLXB' => 'datetime',
            'NhaIn' => 'boolean',
            'DauThau' => 'boolean',
            'BienTap' => 'boolean',
            'LienKet' => 'boolean',
            'NoiBo' => 'boolean',
            'Active' => 'boolean',
            'InUsed' => 'boolean',
            'IsDeleted' => 'boolean',
            'DaGui' => 'boolean',
            'IsCreateQDXB' => 'boolean',
            'Email' => 'string',
        ];
        $normalized = [];
        foreach ($typeFields as $field => $type) {
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
