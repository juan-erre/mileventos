<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rol_id',
        'name',
        'foto',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relacion con el modelo Rol (Un usuario solo puede tener un rol)
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    // Relacion con el modelo Evento (Un usuario puede tener muchos eventos)
    public function eventos(): HasMany                      
    {
        return $this->hasMany(Evento::class, 'user_id');    
    }
    
    // Relacion con el modelo Reserva (Un usuario puede tener muchas reservas)
    public function reservas(): HasMany                      
    {
        return $this->hasMany(Reserva::class, 'user_id');    
    }   

    // Devuelve si el usuario tiene el rol Administrador o no
    public function isAdmin(): bool
    {
        return $this->rol?->rol === 'Administrador';
    }

    // Devuelve si el usuario tiene el rol Organizador o no
    public function isOrganizador(): bool
    {
        return $this->rol?->rol === 'Organizador';
    }
}
