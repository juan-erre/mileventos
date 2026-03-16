<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Entrada extends Model
{
    use HasFactory;

    protected $table='entradas';    

    protected $fillable=['evento_id'];                                                                                              

    // Relación con el modelo Evento (Una entrada solo puede pertenecer a un evento)
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    // Relación con el modelo Reserva (Una entrada solo puede tener una reserva)
    public function reserva(): HasOne
    {
        return $this->hasOne(Reserva::class, 'entrada_id');
    }
}
