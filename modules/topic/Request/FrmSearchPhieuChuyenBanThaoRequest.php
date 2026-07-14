<?php
namespace Modules\Topic\Request;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\FilterPhieuChuyenBanThao;

class FrmSearchPhieuChuyenBanThaoRequest extends FormRequest
{
    protected $casts = [
        'TuKhoa' => 'string',
        'startDate' => 'date',
        'endDate' => 'date',
        'ID_DV' => 'integer',
        'IsDeleted' => 'boolean',
    ];

    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'TuKhoa' => 'sometimes|string|nullable',
            'startDate' => 'sometimes|date|nullable',
            'endDate' => 'sometimes|date|nullable',
            'ID_DV' => 'sometimes|integer|nullable',
            'IsDeleted' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [];
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

    public function toFilter(): FilterPhieuChuyenBanThao
    {
        $validated = $this->validated();
        $filter = new FilterPhieuChuyenBanThao();
        foreach ($this->casts as $key => $type) {
            if (!array_key_exists($key, $validated)) {
                continue;
            }

            $normalizedValue = core_normalize_type_value($type, $validated[$key]);
            if ($type === 'date') {
                $filter->{$key} = $normalizedValue ? new DateTime((string) $normalizedValue) : null;
                continue;
            }
            $filter->{$key} = $normalizedValue;
        }
        $filter->relations = ['sach', 'donvi'];
        return $filter;
    }
}
