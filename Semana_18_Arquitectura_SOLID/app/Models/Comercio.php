<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Comercio extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','nombre'];
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function transacciones(): HasMany { return $this->hasMany(Transaccion::class); }
}
