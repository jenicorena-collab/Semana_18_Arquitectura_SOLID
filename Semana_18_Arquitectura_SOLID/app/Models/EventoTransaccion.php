<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class EventoTransaccion extends Model
{
    protected $table = 'evento_transacciones';
    protected $fillable = ['tipo','detalle','user_id'];
    public function transaccion(): BelongsTo { return $this->belongsTo(Transaccion::class); }
}
