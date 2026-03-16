<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evento extends Model
{
    use HasFactory;
        
    protected $table='eventos';        
                                                                                                          
    protected $fillable=[
        'user_id',
        'categoria_id',
        'ubicacion_id', 
        'titulo',
        'cartel',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'num_entradas',
        'reservas_habilitadas'];   
       
    // Aplica a tus fechas automáticamente Carbon::parse(...) al ser llamadas
    protected $casts = [
        'fecha_inicio' => 'date', 
        'fecha_fin' => 'date',
    ];    
    
    // Relacion con el modelo Usuario (Un evento solo puede pertenecer a un usuario)
    public function user(): BelongsTo                          
    {
        return $this->belongsTo(User::class, 'user_id');         
    }

    // Relación con el modelo Categoria (Un evento solo puede tener una categoría)
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación con el modelo Ubicacion (Un evento solo puede tener una ubicación)
    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    // Relación con el modelo Entrada (Un evento puede tener muchas entradas) 
    public function entradas(): HasMany
    {
        return $this->hasMany(Entrada::class, 'evento_id');
    }

    // Convierte en mayusculas el título
    public function setTituloAttribute($value)
    {
        $this->attributes['titulo'] = mb_strtoupper($value);
    }

    // Calcula la cantidad de entradas libres
    public function entradasLibres()
    {
        return $this->entradas()->doesntHave('reserva')->count();
    }

    // Cuenta todas las entradas que tienen reservas asociadas
    public function totalReservas(): int
    {
        return $this->entradas()->whereHas('reserva')->count();
    }

    // Cuenta las entradas reservadas por un usuario para un evento
    public function entradasReservadasPorUsuario(?int $usuarioId = null): int
    {
        if (!$usuarioId) {
            return 0; // Si no hay usuario logueado, devolvemos 0
        }

        return $this->entradas()
            ->whereHas('reserva', function($q) use ($usuarioId) {
                $q->where('user_id', $usuarioId);
            })
            ->count();
    }

    // Accessor que calcular los meses y días de desarrollo de un evento 
    public function getMesesEventoAttribute()
    {
        $inicio = $this->fecha_inicio->copy()->startOfDay();
        $fin = $this->fecha_fin->copy()->endOfDay();

        $meses = [];
        $actual = $inicio->copy()->startOfMonth();

        while ($actual <= $fin) {
            $anio = $actual->year;
            $mes = $actual->month;

            $primerDiaMes = $actual->copy()->startOfMonth();
            $ultimoDiaMes = $actual->copy()->endOfMonth();

            $desde = max($inicio, $primerDiaMes);
            $hasta = min($fin, $ultimoDiaMes);

            $diasEvento = range($desde->day, $hasta->day);

            $meses[] = [
                'anio' => $anio,
                'mes' => $mes,
                'diasEvento' => $diasEvento,
            ];

            $actual->addMonth();
        }

        return $meses;
    }

    // Desactiva reservas si el evento terminó
    public static function actualizarReservas()
    {
        self::where('reservas_habilitadas', true)
            ->where('fecha_fin', '<=', now())
            ->update(['reservas_habilitadas' => false]);
    }

    // Elimina reservas 24h después de terminar el evento
    public static function limpiarReservas()
    {
        Reserva::whereHas('entrada.evento', function($q) {
            $q->where('fecha_fin', '<=', now()->subDay());
        })->delete();
    }
}
