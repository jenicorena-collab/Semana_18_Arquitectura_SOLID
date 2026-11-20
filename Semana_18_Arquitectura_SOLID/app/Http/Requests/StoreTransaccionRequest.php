<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreTransaccionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'comercio_id' => ['required','integer'],
            'monto' => ['required','numeric','gt:0'],
            'metodo' => ['required', Rule::in(['tarjeta','transferencia','billetera'])]
        ];
    }
}
