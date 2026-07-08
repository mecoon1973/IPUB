<?php

namespace Modules\Topic\Request;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Topic\Object\FilterPhieuDkKhxbCxb;

class FrmSearchPhieuDkKhxbCxbRequest extends FormRequest
{
    protected $casts = [
        'TuKhoa' => 'string',
        'startDate' => 'date',
        'endDate' => 'date',
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
        ];
    }

    public function messages()
    {
        return [
            'TuKhoa.string' => config('label.INPUT_ERROR'),
            'startDate.date' => config('label.INPUT_ERROR'),
            'endDate.date' => config('label.INPUT_ERROR'),
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

    public function toFilter(): FilterPhieuDkKhxbCxb
    {
        $validated = $this->validated();
        $filter = new FilterPhieuDkKhxbCxb();

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

        return $filter;
    }
}
