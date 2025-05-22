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
        Schema::create('recargos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuota_id')->constrained()->onDelete('cascade');
            $table->decimal('porcentaje', 5, 2); // Ej: 10.50%
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('recargos');
    }
};
