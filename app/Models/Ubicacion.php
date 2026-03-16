<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    use HasFactory;
   
    protected $table='ubicaciones';    

    protected $fillable=['provincia'];                                                                                              

    // Relación con el modelo Evento (Una ubicación puede estar en muchos eventos)
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'ubicacion_id');
    }

}   
