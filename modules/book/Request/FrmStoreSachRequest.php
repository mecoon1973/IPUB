<?php
namespace Modules\Book\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreSachRequest extends FormRequest

{
    protected $casts = [
        "id" => "int",
        "MaSo" => "string",
        "BanQuyen" => "boolean",
        "KieuBanQuyen" => "int",
        "GiaBia" => "int",
        "ThongTinBanQuyen" => "string",
        "SoHuuBanQuyen" => "string",
        "BanQuyenTuNgay" => "date",
        "BanQuyenDenNgay" => "date",

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
            "id" => "required|integer",
            "MaSo" => "sometimes|string",
            "BanQuyen" => "sometimes|boolean",
            "KieuBanQuyen" => "sometimes|integer",
            "GiaBia" => "sometimes|integer",
            "ThongTinBanQuyen" => "sometimes|string",
            "SoHuuBanQuyen" => "sometimes|string",
            "BanQuyenTuNgay" => "sometimes|date",
            "BanQuyenDenNgay" => "sometimes|date",
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages() {
        return [
            "id.required" => "ID không được để trống",
            "id.integer" => "ID phải là định dạng số nguyên",
            "MaSo.string" => "Mã số phải là định dạng chuỗi",
            "BanQuyen.boolean" => "Bản quyền phải là định dạng boolean",
            "KieuBanQuyen.integer" => "Kiểu bản quyền phải là định dạng số nguyên",
            "ThongTinBanQuyen.string" => "Thông tin bản quyền phải là định dạng chuỗi",
            "SoHuuBanQuyen.string" => "Sở hữu bản quyền phải là định dạng chuỗi",
            "BanQuyenTuNgay.date" => "Ngày bắt đầu bản quyền phải là định dạng date",
            "BanQuyenDenNgay.date" => "Ngày kết thúc bản quyền phải là định dạng date",
            "GiaBia.integer" => "Giá bìa phải là định dạng số nguyên",
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
