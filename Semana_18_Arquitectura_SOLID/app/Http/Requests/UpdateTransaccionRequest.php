<?php
namespace App\Http\Requests;
use App\Enums\EstadoTransaccion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateTransaccionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return ['estado' => ['required', Rule::enum(EstadoTransaccion::class)]];
    }
}
