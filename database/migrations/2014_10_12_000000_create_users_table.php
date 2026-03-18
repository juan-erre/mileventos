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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id auto-incremental
            $table->foreignId('rol_id')->constrained('roles')->cascadeOnUpdate()->restrictOnDelete(); // constrained('roles') automáticamente genera la FK hacia la columna id de la tabla roles. Se actualiza en cascada y restringe eliminar el padre si existe hijo  
            $table->string('name', 100);
            $table->string('foto')->nullable(); // Equivale por defecto a un VARCHAR(255) en la base de datos
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable(); // Se usa para guardar la fecha y hora en que el usuario verificó su email
            $table->string('password'); // Equivale por defecto a un VARCHAR(255) en la base de datos
            $table->rememberToken(); // Para recordar un usuario que inicia sesión
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
