<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Categoria extends Model
{
    use HasFactory;
   
    protected $table='categorias';   

    protected $fillable=['categoria'];                                                                                              

    // Relación con el modelo Evento (Una categoria puede estar en muchos eventos)
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class, 'categoria_id');
    }

}   
