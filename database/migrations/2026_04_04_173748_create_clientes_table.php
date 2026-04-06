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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('cedula',10)->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->timestamps();
        });

        DB::table('clientes')->insert ([
            ['cedula' => '1850972280', 'nombre' => 'Josue', 'apellido' => 'Llumitasig' , 'created_at' => now(), 'updated_at' => now()],
            ['cedula' => '1857487985', 'nombre' => 'Juan', 'apellido' => 'Llumitasig', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
