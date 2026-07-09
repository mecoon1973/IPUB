<?php

namespace Modules\Topic\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FrmCapMaIsbnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'idPhieu' => 'required|integer',
            'listIsbn' => 'sometimes|array',
            'listIsbn.*.id' => 'required|integer',
            'listIsbn.*.ISBNCode' => 'sometimes|string|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'idPhieu.required' => 'Không xác định được phiếu trình CXB',
        ];
    }

    public function toPayload(): array
    {
        $idPhieu = (int) $this->input('idPhieu');
        $listIsbn = $this->input('listIsbn', []);
        if (!is_array($listIsbn)) {
            $listIsbn = [];
        }

        $normalized = [];
        foreach ($listIsbn as $item) {
            $normalized[] = [
                'id' => (int) ($item['id'] ?? 0),
                'ISBNCode' => trim((string) ($item['ISBNCode'] ?? '')),
            ];
        }

        return [
            'idPhieu' => $idPhieu,
            'listIsbn' => $normalized,
        ];
    }
}
