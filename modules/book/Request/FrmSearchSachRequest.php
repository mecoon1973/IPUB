<?php
namespace Modules\Book\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Book\Object\FilterSach;

class FrmSearchSachRequest extends FormRequest
{
    protected $casts = [
        'MaSo' => 'string',
        'title' => 'string',
        'ID_MangSach' => 'int',
        'ID_DonVi' => 'int',
        'NamXuatBan' => 'string',
        'NamTaiBan' => 'string',
        'HTXB' => 'int',
        'NgayDK' => 'array',
        'relations' => 'array',
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
            'MaSo' => 'sometimes|string',
            'title' => 'sometimes|string',
            'ID_MangSach' => 'sometimes|int',
            'ID_DonVi' => 'sometimes|int',
            'NamXuatBan' => 'sometimes|string',
            'NamTaiBan' => 'sometimes|string',
            'HTXB' => 'sometimes|int',
            'NgayDK' => 'sometimes|array',
            'NgayDK.*' => 'sometimes|date',
            'relations' => 'sometimes|array',
        ];
    }

    public function messages() {
        return [
            'MaSo.string' => config("label.INPUT_ERROR"),
            'title.string' => config("label.INPUT_ERROR"),
            'ID_MangSach.int' => config("label.INPUT_ERROR"),
            'ID_DonVi.int' => config("label.INPUT_ERROR"),
            'NamXuatBan.string' => config("label.INPUT_ERROR"),
            'NamTaiBan.string' => config("label.INPUT_ERROR"),
            'HTXB.int' => config("label.INPUT_ERROR"),
            'NgayDK.array' => config("label.INPUT_ERROR"),
            'NgayDK.*.date' => config("label.INPUT_ERROR"),
            'relations.array' => config("label.INPUT_ERROR"),
        ];
    }

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
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterSach.
     *
     * @return FilterSach
     */
    public function toFilter() : FilterSach {
        $validated = $this->validated();
        $filter = new FilterSach();
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
