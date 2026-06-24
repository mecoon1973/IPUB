<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStorePhieuDkDetaiRequest extends FormRequest
{
    /**
     * Map field → kiểu chuẩn hoá trong {@see prepareForValidation()} qua `core_normalize_type_value`.
     * Với `boolean`: chuỗi "true"/"false"/"1"/"0" (do jQuery form-urlencoded) → bool thật, khớp rule `boolean` của Laravel.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'HTXB' => 'boolean',
        'BanQuyen' => 'boolean',
        'IsXetDuyet' => 'boolean',
        'LaDeTaiDich' => 'boolean',
        'PTXB' => 'boolean',
        'IsSachDienTu' => 'boolean',
        'CanhBao' => 'boolean',
        'DungLuongTep' => 'string',
        'SoTrangDK' => 'integer',
        'Rong' => 'string',
        'Dai' => 'string',
        'TenNguyenBan' => 'string',
        'tenrutgon' => 'string',
        'DiaChi' => 'string',
        'BienTapVien' => 'string',
        'DichGia' => 'string',
        'NguDuocDich' => 'string',
        'ThongTinSachDich' => 'string',
        'NguXuatBan' => 'string',
        'DeTaiTuongTu' => 'string',
        'DC_TieuThu' => 'string',
        'DeCuong' => 'string',
        'DiaChiCungCap' => 'string',
        'DinhDangTep' => 'string',
        'idListBTV' => 'array',
        'LanTaiBan' => 'integer',
        'ID_TuSach' => 'integer',
        'GiaBia' => 'integer',
        'ID_DonViLK' => 'integer',
        'BanQuyenDenNgay' => 'date',
        'DenNgayHDBS' => 'date',
        'KieuBanQuyen' => 'integer',
        'BanQuyenTuNgay' => 'date',
        'ID_DonVi' => 'integer',
        'ID_LoaiXBP' => 'integer',
        'ID_BoSach' => 'integer',
        'ID_MangSach' => 'integer',
        'TypeLuaTuoi' => 'integer',
        'LuaTuoi' => 'string',
        'ID_Lop' => 'integer',
        'ID_MonHoc' => 'integer',
        'SoLuongDK' => 'integer',
        'ThongTinBanQuyen' => 'string',
        'SoHuuBanQuyen' => 'string',
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
            // các field bắt buộc
            "NgayDK" => "required|date",
            "TenDeTai" => "required|string",
            "TacGia" => "required|string",
            "HTXB" => "required|boolean",
            "NamXuatBan" => "required|string",
            "MauInRuot" => "required|integer",
            "MauInBia" => "required|integer",
            "SoHDBS" => "required|string",
            "NgayKyHDBS" => "required|date",
            "KieuHDBS" => "required|integer",
            "TuNgayHDBS" => "required|date",
            "ID_LoaiXBP" => "required|integer",
            "ID_BoSach" => "required|integer",
            "ID_MangSach" => "required|integer",
            "TypeLuaTuoi" => "required|integer",
            "LuaTuoi" => "required|string",
            "ID_Lop" => "required|integer",
            "ID_MonHoc" => "required|integer",
            "SoLuongDK" => "required|integer",
            "ThongTinBanQuyen" => "required|string",
            "SoHuuBanQuyen" => "required|string",
            "BanQuyen" => "required|boolean",
            "KieuBanQuyen" => "required|integer",
            "BanQuyenTuNgay" => "required|date",
            "ID_DonVi" => "required|integer",
            "IsXetDuyet" => "required|boolean",


            // các field bắt buộc nhưng có option có thể chọn hay không
            "DungLuongTep" => "sometimes|nullable|integer",
            "SoTrangDK" => "sometimes|integer",
            "Rong" => "sometimes|string",
            "Dai" => "sometimes|string",
            "BanQuyenDenNgay" => "sometimes|date",
            "DenNgayHDBS" => "sometimes|date",

            // các field có thể có hoặc không gửi
            "TenNguyenBan" => "sometimes|string",
            "tenrutgon" => "sometimes|string",
            "DiaChi" => "sometimes|string",
            "BienTapVien" => "sometimes|string",
            "idListBTV" => "sometimes|array",
            "LaDeTaiDich" => "sometimes|boolean",
            "DichGia" => "sometimes|string",
            "NguDuocDich" => "sometimes|string",
            "ThongTinSachDich" => "sometimes|string",
            "NguXuatBan" => "sometimes|string",
            "LanTaiBan" => "sometimes|integer",
            "PTXB" => "sometimes|boolean",
            "ID_TuSach" => "sometimes|integer",
            "GiaBia" => "sometimes|integer",
            "ID_DonViLK" => "sometimes|integer",
            "IsSachDienTu" => "sometimes|boolean",
            "CanhBao" => "sometimes|boolean",
            "DeTaiTuongTu" => "sometimes|string",
            "DC_TieuThu" => "sometimes|string",
            "DeCuong" => "sometimes|string",
            "DiaChiCungCap" => "sometimes|string",
            "DinhDangTep" => "sometimes|string",

        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "NgayDK.required" => "Ngày đăng ký là bắt buộc",
            "NgayDK.date" => "Ngày đăng ký phải là ngày",
            "TenDeTai.required" => "Tên đề tài là bắt buộc",
            "TenDeTai.string" => "Tên đề tài phải là chuỗi",
            "TacGia.required" => "Tác giả là bắt buộc",
            "TacGia.string" => "Tác giả phải là chuỗi",
            "HTXB.required" => "HTXB là bắt buộc",
            "HTXB.boolean" => "HTXB phải là boolean",
            "NamXuatBan.required" => "Năm xuất bản là bắt buộc",
            "NamXuatBan.string" => "Năm xuất bản phải là chuỗi",
            "MauInRuot.required" => "Mẫu in rột là bắt buộc",
            "MauInRuot.integer" => "Mẫu in rột phải là số nguyên",
            "MauInBia.required" => "Mẫu in bia là bắt buộc",
            "MauInBia.integer" => "Mẫu in bia phải là số nguyên",
            "SoHDBS.required" => "Số HDBS là bắt buộc",
            "SoHDBS.string" => "Số HDBS phải là chuỗi",
            "NgayKyHDBS.required" => "Ngày ký HDBS là bắt buộc",
            "NgayKyHDBS.date" => "Ngày ký HDBS phải là ngày",
            "KieuHDBS.required" => "Kiểu HDBS là bắt buộc",
            "KieuHDBS.integer" => "Kiểu HDBS phải là số nguyên",
            "TuNgayHDBS.required" => "Ngày bắt đầu HDBS là bắt buộc",
            "TuNgayHDBS.date" => "Ngày bắt đầu HDBS phải là ngày",
            "ID_LoaiXBP.required" => "ID Loại XBP là bắt buộc",
            "ID_LoaiXBP.integer" => "ID Loại XBP phải là số nguyên",
            "ID_BoSach.required" => "ID Bo Sách là bắt buộc",
            "ID_BoSach.integer" => "ID Bo Sách phải là số nguyên",
            "ID_MangSach.required" => "ID Mang Sách là bắt buộc",
            "ID_MangSach.integer" => "ID Mang Sách phải là số nguyên",
            "TypeLuaTuoi.required" => "Loại tuổi là bắt buộc",
            "TypeLuaTuoi.integer" => "Loại tuổi phải là số nguyên",
            "LuaTuoi.required" => "Tuổi là bắt buộc",
            "LuaTuoi.string" => "Tuổi phải là chuỗi",
            "ID_Lop.required" => "ID Lớp là bắt buộc",
            "ID_Lop.integer" => "ID Lớp phải là số nguyên",
            "ID_MonHoc.required" => "ID Môn học là bắt buộc",
            "ID_MonHoc.integer" => "ID Môn học phải là số nguyên",
            "SoLuongDK.required" => "Số lượng đăng ký là bắt buộc",
            "SoLuongDK.integer" => "Số lượng đăng ký phải là số nguyên",
            "ThongTinBanQuyen.required" => "Thông tin bản quyền là bắt buộc",
            "ThongTinBanQuyen.string" => "Thông tin bản quyền phải là chuỗi",
            "SoHuuBanQuyen.required" => "Số hữu bản quyền là bắt buộc",
            "SoHuuBanQuyen.string" => "Số hữu bản quyền phải là chuỗi",
            "BanQuyen.required" => "Bản quyền là bắt buộc",
            "BanQuyen.boolean" => "Bản quyền phải là boolean",
            "KieuBanQuyen.required" => "Kiểu bản quyền là bắt buộc",
            "KieuBanQuyen.integer" => "Kiểu bản quyền phải là số nguyên",
            "BanQuyenTuNgay.required" => "Ngày bắt đầu bản quyền là bắt buộc",

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
