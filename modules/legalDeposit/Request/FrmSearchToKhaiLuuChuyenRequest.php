<?php
namespace Modules\LegalDeposit\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Modules\LegalDeposit\Object\FilterToKhaiLuuChuyen;
class FrmSearchToKhaiLuuChuyenRequest extends FormRequest
{
    protected $casts = [
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
        ];
    }
    public function messages() {
        return [
        ];
    }
    protected function prepareForValidation(): void
    {
        $normalized = [];
        foreach ($this->casts as $field => $type) {
            $normalized[$field] = core_normalize_type_value($type, $this->input($field));
        }
        if (!empty($normalized)) {
            $this->merge($normalized);
        }
    }
    /**
     * Chuyển đổi dữ liệu đầu vào thành đối tượng FilterToKhaiLuuChuyen.
     *
     * @return FilterToKhaiLuuChuyen
     */
    public function toFilter() : FilterToKhaiLuuChuyen {
        $validated = $this->validated();
        $filter = new FilterToKhaiLuuChuyen();
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
