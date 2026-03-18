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
        Schema::create('eventos', function (Blueprint $table) {          
            $table->id(); // id auto-incremental
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete(); // Se actualiza y elimina en cascada (Si se elimina el usuario se elimina el evento)  
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnUpdate()->restrictOnDelete(); // Se actualiza en cascada y restringe eliminar el padre si existe el hijo  
            $table->foreignId('ubicacion_id')->constrained('ubicaciones')->cascadeOnUpdate()->restrictOnDelete(); // Se actualiza en cascada y restringe eliminar el padre si existe el hijo 
            $table->string('titulo',100);
            $table->string('cartel')->nullable(); // equivale por defecto a un VARCHAR(255) en la base de datos
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->text('descripcion')->nullable(); 
            $table->integer('num_entradas')->nullable(); 
            $table->boolean('reservas_habilitadas')->default(true);
            $table->timestamps();
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');   
    }
};