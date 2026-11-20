<?php
namespace App\Services;
use App\Enums\EstadoTransaccion;
use App\Models\Transaccion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
class TransaccionService
{
    public function __construct(private CalculadoraComision $calculadora) {}
    public function registrar(User $usuario, array $datos): Transaccion
    {
        $comercio = $usuario->comercios()->findOrFail($datos['comercio_id']);
        $comision = $this->calculadora->calcular($datos['metodo'], (float)$datos['monto']);
        $transaccion = DB::transaction(function () use ($comercio, $datos, $comision) {
            $t = $comercio->transacciones()->create([
                'monto' => $datos['monto'], 'metodo' => $datos['metodo'],
                'comision' => $comision, 'estado' => EstadoTransaccion::Iniciada,
            ]);
            $t->eventos()->create(['tipo' => 'creada']);
            return $t;
        });
        Log::info("Transacción {$transaccion->id} registrada");
        return $transaccion;
    }
    public function cambiarEstado(User $usuario, Transaccion $transaccion, EstadoTransaccion $nuevo): Transaccion
    {
        if ($transaccion->comercio->user_id !== $usuario->id) {
            abort(404, 'No encontrado');
        }
        if ($transaccion->estado !== EstadoTransaccion::Iniciada) {
            throw ValidationException::withMessages(['estado' => 'La transacción ya fue cerrada']);
        }
        $anterior = $transaccion->estado->value;
        DB::transaction(function () use ($transaccion, $nuevo, $anterior, $usuario) {
            $transaccion->update(['estado' => $nuevo]);
            $transaccion->eventos()->create([
                'tipo' => 'cambio_estado', 'detalle' => "{$anterior} -> {$nuevo->value}", 'user_id' => $usuario->id
            ]);
        });
        Log::info("Transacción {$transaccion->id}: {$anterior} -> {$nuevo->value}");
        return $transaccion->refresh();
    }
}
