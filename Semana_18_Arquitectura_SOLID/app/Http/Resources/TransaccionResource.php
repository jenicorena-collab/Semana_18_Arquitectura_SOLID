<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class TransaccionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'comercio_id' => $this->comercio_id,
            'monto' => $this->monto, 'metodo' => $this->metodo,
            'comision' => $this->comision, 'estado' => $this->estado->value,
        ];
    }
}
