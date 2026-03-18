<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id(); // id auto-incremental
            $table->foreignId('entrada_id')->constrained('entradas')->cascadeOnUpdate()->restrictOnDelete(); // Se actualiza en cascada y restringe eliminar el padre si existe el hijo (No se puede eliminar la entrada si existe reserva) 
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete(); // Se actualiza y elimina en cascada (Si se elimina el usuario se elimina la reserva)  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
