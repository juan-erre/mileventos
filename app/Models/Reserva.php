<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $table='reservas';    

    protected $fillable=[
            'entrada_id',
            'user_id'
        ];                                                                                               

    // Relación con el modelo Entrada (Una reserva solo puede pertenecer a una entrada)
    public function entrada(): BelongsTo
    {
        return $this->belongsTo(Entrada::class, 'entrada_id');
    }

    // Relación con el modelo User (Una reserva solo puede pertenecer a un usuario)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
