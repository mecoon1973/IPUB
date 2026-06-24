<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmStoreQDInRequest extends FormRequest
{

    protected $casts = [
        'CanCu' => 'string',
        'DiaDanh' => 'string',
        'HTXB' => 'integer',
        'ID_DVQD_VMS' => 'integer',
        'ID_DV_QD' => 'integer',
        'ID_MangSachQDIN' => 'integer',
        'ID_NguoiKi' => 'integer',
        'ID_VMS' => 'string',
        'MaDonviQD' => 'string',
        'NamKeHoach' => 'string',
        'NoiNhan' => 'string',
        'SoQD' => 'string',
        'TenDonViQD' => 'string',
        'TenDonVi_VMS' => 'string',
        'TenNguoiKi' => 'string',
        'TieuDe' => 'string',
        'UserName_VMS' => 'string',
        'SoQDTuTang' => 'integer',
        'NgayQD' => 'datetime',
        'DaGui' => 'boolean',
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
            'CanCu' => 'sometimes|string',
            'DiaDanh' => 'sometimes|string',
            'HTXB' => 'sometimes|integer',
            'ID_DVQD_VMS' => 'sometimes|integer',
            'ID_DV_QD' => 'sometimes|integer',
            'ID_MangSachQDIN' => 'sometimes|integer',
            'ID_NguoiKi' => 'sometimes|integer',
            'ID_VMS' => 'sometimes|string',
            'MaDonviQD' => 'sometimes|string',
            'NamKeHoach' => 'sometimes|string',
            'NoiNhan' => 'sometimes|string',
            'SoQD' => 'sometimes|string',
            'TenDonViQD' => 'sometimes|string',
            'TenDonVi_VMS' => 'sometimes|string',
            'TenNguoiKi' => 'sometimes|string',
            'TieuDe' => 'sometimes|string',
            'UserName_VMS' => 'sometimes|string',
            'SoQDTuTang' => 'sometimes|integer',
            'NgayQD' => 'sometimes|datetime',
            'DaGui' => 'sometimes|boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "CanCu.string" => config("label.INPUT_ERROR"),
            "DiaDanh.string" => config("label.INPUT_ERROR"),
            "HTXB.integer" => config("label.INPUT_ERROR"),
            "ID_DVQD_VMS.integer" => config("label.INPUT_ERROR"),
            "ID_DV_QD.integer" => config("label.INPUT_ERROR"),
            "ID_MangSachQDIN.integer" => config("label.INPUT_ERROR"),
            "ID_NguoiKi.integer" => config("label.INPUT_ERROR"),
            "ID_VMS.string" => config("label.INPUT_ERROR"),
            "MaDonviQD.string" => config("label.INPUT_ERROR"),
            "NamKeHoach.string" => config("label.INPUT_ERROR"),
            "NoiNhan.string" => config("label.INPUT_ERROR"),
            "SoQD.string" => config("label.INPUT_ERROR"),
            "TenDonViQD.string" => config("label.INPUT_ERROR"),
            "TenDonVi_VMS.string" => config("label.INPUT_ERROR"),
            "TenNguoiKi.string" => config("label.INPUT_ERROR"),
            "TieuDe.string" => config("label.INPUT_ERROR"),
            "UserName_VMS.string" => config("label.INPUT_ERROR"),
            "SoQDTuTang.integer" => config("label.INPUT_ERROR"),
            "NgayQD.datetime" => config("label.INPUT_ERROR"),
            "DaGui.boolean" => config("label.INPUT_ERROR"),
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];
        foreach ($this->casts as $field => $type) {
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
