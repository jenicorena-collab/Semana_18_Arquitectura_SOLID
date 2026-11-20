<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $fillable = ['name','email','password'];
    protected $hidden = ['password','remember_token'];
    public function comercios(): HasMany { return $this->hasMany(Comercio::class); }
}
