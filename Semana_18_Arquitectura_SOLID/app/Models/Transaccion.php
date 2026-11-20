<?php
namespace App\Models;
use App\Enums\EstadoTransaccion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Transaccion extends Model
{
    protected $table = 'transacciones';
    protected $fillable = ['comercio_id','monto','metodo','comision','estado'];
    protected function casts(): array { return ['estado' => EstadoTransaccion::class, 'monto'=>'decimal:2', 'comision'=>'decimal:2']; }
    public function comercio(): BelongsTo { return $this->belongsTo(Comercio::class); }
    public function eventos(): HasMany { return $this->hasMany(EventoTransaccion::class); }
}
