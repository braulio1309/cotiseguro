<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Información básica
            $table->decimal('discount', 8, 2)->default(0);
            $table->json('ages'); // Guarda edades como array: [32, 10, 5]

            // Coberturas como booleans
            $table->boolean('maternidad')->default(false);
            $table->boolean('vida')->default(false);
            $table->boolean('funerario')->default(false);
            $table->boolean('viaje')->default(false);
            $table->boolean('medico')->default(false);

            // Empresas seleccionadas en la cotización
            $table->json('companies'); // Ej: ["Empresa A", "Empresa B"]

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotizaciones');
    }
};
