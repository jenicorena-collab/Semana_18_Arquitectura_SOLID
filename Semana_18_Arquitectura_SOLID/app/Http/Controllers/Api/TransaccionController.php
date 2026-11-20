<?php
namespace App\Http\Controllers\Api;
use App\Enums\EstadoTransaccion;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransaccionRequest;
use App\Http\Requests\UpdateTransaccionRequest;
use App\Http\Resources\TransaccionResource;
use App\Models\Transaccion;
use App\Services\TransaccionService;
class TransaccionController extends Controller
{
    public function __construct(private TransaccionService $servicio) {}
    public function store(StoreTransaccionRequest $request)
    {
        $t = $this->servicio->registrar($request->user(), $request->validated());
        return (new TransaccionResource($t))->response()->setStatusCode(201);
    }
    public function update(UpdateTransaccionRequest $request, Transaccion $transaccion)
    {
        $estado = EstadoTransaccion::from($request->validated('estado'));
        $t = $this->servicio->cambiarEstado($request->user(), $transaccion, $estado);
        return new TransaccionResource($t);
    }
}
