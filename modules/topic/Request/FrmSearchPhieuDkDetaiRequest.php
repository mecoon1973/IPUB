<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\FilterPhieuDkDetai;

class FrmSearchPhieuDkDetaiRequest extends FormRequest
{
    protected $casts = [
        "MaSo" => "string",
        "TenDeTai" => "string",
        "TacGia" => "string",
        "NamXuatBan" => "string",
        "BienTapVien" => "string",
        "ID_MangSach" => "integer",
        "HTXB" => "integer",
        "ID_DonVi" => "integer",
        "TrangThai" => "integer",
        "IsDeleted" => "boolean",
        "NgayDK" => "array|datetime",
        "limit" => "integer",
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
            "MaSo" => "nullable|string",
            "TenDeTai" => "nullable|string",
            "TacGia" => "nullable|string",
            "NamXuatBan" => "nullable|string",
            "BienTapVien" => "nullable|string",
            "ID_MangSach" => "nullable|integer",
            "HTXB" => "nullable|integer",
            "ID_DonVi" => "nullable|integer",
            "TrangThai" => "nullable|integer",
            "IsDeleted" => "nullable|boolean",
            "NgayDK" => "nullable|array",
            "NgayDK.*" => "nullable|date",
            "limit" => "nullable|integer",
        ];
    }

    public function messages() {
        return [
            "IsDeleted.boolean" => config("label.INPUT_ERROR"),
            "NgayDK.array" => config("label.INPUT_ERROR"),
            "HTXB.integer" => config("label.INPUT_ERROR"),
            "ID_MangSach.integer" => config("label.INPUT_ERROR"),
            "ID_DonVi.integer" => config("label.INPUT_ERROR"),
            "TrangThai.integer" => config("label.INPUT_ERROR"),
            "limit.integer" => config("label.INPUT_ERROR"),
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];

        foreach ($this->casts as $field => $type) {
            if (!$this->has($field)) {
                continue;
            }

            $value = $this->input($field);

            $normalized[$field] = core_normalize_type_value($type, $value);
        }

        if (!empty($normalized)) {
            $this->merge($normalized);
        }
    }

    /**
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterPhieuDkDetai.
     *
     * @return FilterPhieuDkDetai
     */

    public function toFilter() : FilterPhieuDkDetai {

        $validated = $this->validated();
        $filter = new FilterPhieuDkDetai();

        foreach ($this->casts as $key => $type) {
            if (!array_key_exists($key, $validated)) {
                continue;
            }
            settype($validated[$key], $type);
            $filter->{$key} = $validated[$key];
        }

        return $filter;

    }
}
