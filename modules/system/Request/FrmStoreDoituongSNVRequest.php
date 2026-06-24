<?php
namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreDoituongSNVRequest extends FormRequest

{
    protected $casts = [

        "id" => "int",
        "TenDonVi" => "string",
        "ThuTu" => "int",
        "listLoaiSNV" => "array",

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
            "id" => "sometimes|integer",
            "TenDonVi" => "required|string",
            "ThuTu" => "required|integer",
            "listLoaiSNV" => "required|array",
            "listLoaiSNV.*.id" => "required|integer",
            "listLoaiSNV.*.SoLuong" => "required|integer",
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages() {
        return [
            "TenDonVi.required" => config("label.INPUT_ERROR"),
            "ThuTu.required" => config("label.INPUT_ERROR"),
            "id.integer" => config("label.INPUT_ERROR"),
            "TenDonVi.string" => config("label.INPUT_ERROR"),
            "ThuTu.integer" => config("label.INPUT_ERROR"),
            "listLoaiSNV.required" => config("label.INPUT_ERROR"),
            "listLoaiSNV.*.id.required" => config("label.INPUT_ERROR"),
            "listLoaiSNV.*.id.integer" => config("label.INPUT_ERROR"),
            "listLoaiSNV.*.SoLuong.required" => config("label.INPUT_ERROR"),
            "listLoaiSNV.*.SoLuong.integer" => config("label.INPUT_ERROR"),
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
            if ($field === "listLoaiSNV") {
                $items = $this->input($field);
                $normalized[$field] = is_array($items)
                    ? array_map(static function ($item) {
                        if (!is_array($item)) {
                            return $item;
                        }
                        return [
                            "id" => core_normalize_type_value("int", $item["id"] ?? 0),
                            "SoLuong" => core_normalize_type_value("int", $item["SoLuong"] ?? 0),
                        ];
                    }, $items)
                    : $items;
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
