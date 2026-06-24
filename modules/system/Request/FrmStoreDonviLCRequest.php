<?php
namespace Modules\System\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreDonviLCRequest extends FormRequest

{
    protected $casts = [
        "id" => "int",
        "Ten" => "string",
        "ThuTu" => "int",
        "IsDeleted" => "boolean",
        "InUsed" => "boolean",
    ];

    /** @var array<string, string> */
    protected $LoaiXbpLcItemCasts = [
        "ID_LOAI_XBP_LC" => "int",
        "SoLuong" => "int",
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
            "Ten" => "required|string",
            "ThuTu" => "required|integer",
            "IsDeleted" => "sometimes|boolean",
            "InUsed" => "sometimes|boolean",
            "LoaiXbpLc" => "required|array",
            "LoaiXbpLc.*.ID_LOAI_XBP_LC" => "required|integer",
            "LoaiXbpLc.*.SoLuong" => "required|integer",
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages() {
        return [
            "Ten.required" => config("label.INPUT_ERROR"),
            "ThuTu.required" => config("label.INPUT_ERROR"),
            "IsDeleted.boolean" => config("label.INPUT_ERROR"),
            "InUsed.boolean" => config("label.INPUT_ERROR"),
            "LoaiXbpLc.required" => config("label.INPUT_ERROR"),
            "LoaiXbpLc.*.ID_LOAI_XBP_LC.required" => config("label.INPUT_ERROR"),
            "LoaiXbpLc.*.SoLuong.required" => config("label.INPUT_ERROR"),
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

        if ($this->has('LoaiXbpLc')) {
            $items = $this->input('LoaiXbpLc');
            if (is_array($items)) {
                $normalized['LoaiXbpLc'] = array_map(function ($item) {
                    if (!is_array($item)) {
                        return $item;
                    }
                    foreach ($this->LoaiXbpLcItemCasts as $key => $type) {
                        if (array_key_exists($key, $item)) {
                            $item[$key] = core_normalize_type_value($type, $item[$key]);
                        }
                    }
                    return $item;
                }, $items);
            }
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
