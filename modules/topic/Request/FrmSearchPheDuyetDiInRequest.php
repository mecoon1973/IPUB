<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\FilterPheDuyetDiIn;

class FrmSearchPheDuyetDiInRequest extends FormRequest
{
    protected $casts = [
        'TenSach' => 'string',
        'MaSo' => 'string',
        'NamXBTB' => 'string',
        'ID_DonVi' => 'integer',
        'LocTheo' => 'integer',
        'TrangThai' => 'integer',
        'idsDeTai' => 'array',
    ];

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'TenSach' => 'nullable|string',
            'MaSo' => 'nullable|string',
            'NamXBTB' => 'nullable|string',
            'ID_DonVi' => 'nullable|integer',
            'LocTheo' => 'nullable|integer',
            'TrangThai' => 'nullable|integer',
            'idsDeTai' => 'nullable|array',
            'idsDeTai.*' => 'integer',
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

    public function toFilter(): FilterPheDuyetDiIn
    {
        $validated = $this->validated();
        $filter = new FilterPheDuyetDiIn();

        foreach ($this->casts as $key => $type) {
            if (!array_key_exists($key, $validated)) {
                continue;
            }
            if ($key === 'idsDeTai') {
                $filter->idsDeTai = array_values(array_filter(array_map('intval', $validated[$key] ?? [])));
                continue;
            }
            settype($validated[$key], $type);
            $filter->{$key} = $validated[$key];
        }

        return $filter;
    }
}
