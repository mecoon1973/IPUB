<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmSearchDocDuyetHDXBNXBGDVNRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'ids' => 'nullable|array|min:1',
            'ids.*' => 'integer|min:1',
        ];
    }

    /** @return int[] */
    public function getIdsDeTai(): array
    {
        $ids = $this->validated()['ids'] ?? [];
        if (!is_array($ids)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('intval', $ids))));
    }
}
