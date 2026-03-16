<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;
    
    protected $table='roles'; 
    
    protected $fillable = ['rol'];

    // Relación con el modelo User (Cada Rol puede estar en muchos usuarios)
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'rol_id');
    }
}
