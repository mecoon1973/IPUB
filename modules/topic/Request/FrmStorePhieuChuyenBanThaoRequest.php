<?php
namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStorePhieuChuyenBanThaoRequest extends FormRequest

{
    protected $casts = [
        'id' => 'integer',
        'ID_Sach' => 'integer',
        'ID_DeTai' => 'integer',
        'ID_DV' => 'integer',
        'ID_MangSach' => 'integer',
        'ID_BTVNhan' => 'integer',
        'ID_ListBienTapVien' => 'string',
        'ID_NguoiKy' => 'integer',
        'NgayGiao' => 'datetime',
        'NgayNhan' => 'datetime',
        'NguoiGiao' => 'string',
        'NguoiNhan' => 'string',
        'TacGia' => 'string',
        'BienTapVien' => 'string',
        'SoTrang' => 'integer',
        'SoTrangRuotSach' => 'integer',
        'SoTrangPhuBan' => 'integer',
        'SoBo' => 'integer',
        'SoBoBanThao' => 'integer',
        'SoBoBiaMau' => 'integer',
        'SoBoPhimBia' => 'integer',
        'Dai' => 'integer',
        'Rong' => 'integer',
        'MauInBia' => 'integer',
        'MauInRout' => 'integer',
        'SoMauInBia' => 'integer',
        'MaDVIN' => 'string',
        'LanIn' => 'integer',
        'CheBanCan' => 'boolean',
        'CoAoBoc' => 'boolean',
        'LoaiBia' => 'boolean',
        'LoaiPhieu' => 'boolean',
        'IsSachDienTu' => 'boolean',
        'DiaChiCungCap' => 'string',
        'DinhDangTep' => 'string',
        'DungLuongTep' => 'string',
        'GhiChu' => 'string',
    ];

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
            'id' => 'sometimes|integer',
            'ID_Sach' => 'required|integer',
            'ID_DeTai' => 'sometimes|integer|nullable',
            'ID_DV' => 'sometimes|integer|nullable',
            'ID_MangSach' => 'sometimes|integer|nullable',
            'ID_BTVNhan' => 'sometimes|integer|nullable',
            'ID_ListBienTapVien' => 'sometimes|string|nullable',
            'ID_NguoiKy' => 'sometimes|integer|nullable',
            'NgayGiao' => 'sometimes|date|nullable',
            'NgayNhan' => 'sometimes|date|nullable',
            'NguoiGiao' => 'sometimes|string|nullable',
            'NguoiNhan' => 'sometimes|string|nullable',
            'TacGia' => 'sometimes|string|nullable',
            'BienTapVien' => 'sometimes|string|nullable',
            'SoTrang' => 'sometimes|integer|nullable',
            'SoTrangRuotSach' => 'sometimes|integer|nullable',
            'SoTrangPhuBan' => 'sometimes|integer|nullable',
            'SoBo' => 'sometimes|integer|nullable',
            'SoBoBanThao' => 'sometimes|integer|nullable',
            'SoBoBiaMau' => 'sometimes|integer|nullable',
            'SoBoPhimBia' => 'sometimes|integer|nullable',
            'Dai' => 'sometimes|integer|nullable',
            'Rong' => 'sometimes|integer|nullable',
            'MauInBia' => 'sometimes|integer|nullable',
            'MauInRout' => 'sometimes|integer|nullable',
            'SoMauInBia' => 'sometimes|integer|nullable',
            'MaDVIN' => 'sometimes|string|nullable',
            'LanIn' => 'sometimes|integer|nullable',
            'CheBanCan' => 'sometimes|boolean',
            'CoAoBoc' => 'sometimes|boolean',
            'LoaiBia' => 'sometimes|boolean',
            'LoaiPhieu' => 'sometimes|boolean',
            'IsSachDienTu' => 'sometimes|boolean',
            'DiaChiCungCap' => 'sometimes|string|nullable',
            'DinhDangTep' => 'sometimes|string|nullable',
            'DungLuongTep' => 'sometimes|string|nullable',
            'GhiChu' => 'sometimes|string|nullable',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages() {
        return [
            'ID_Sach.required' => 'Vui lòng chọn sách',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Một số cột trong DB có thể là số nhưng phía client gửi lên (hoặc server trả về) dạng number,
        // trong khi rule đang yêu cầu string -> cần chuẩn hoá number/bool thành string.
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