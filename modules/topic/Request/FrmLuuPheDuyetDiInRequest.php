<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmLuuPheDuyetDiInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|min:1',
            'items.*.YKienDocBanThao' => 'nullable|string',
            'items.*.XetDuyetBanThao' => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $items = $this->input('items', []);
        if (!is_array($items)) {
            return;
        }

        foreach ($items as $index => $item) {
            if (!is_array($item) || !array_key_exists('XetDuyetBanThao', $item)) {
                continue;
            }

            $items[$index]['XetDuyetBanThao'] = filter_var(
                $item['XetDuyetBanThao'],
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            ) ?? false;
        }

        $this->merge(['items' => $items]);
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Không có sách để lưu',
            'items.min' => 'Không có sách để lưu',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function getItems(): array
    {
        return $this->validated()['items'];
    }
}
