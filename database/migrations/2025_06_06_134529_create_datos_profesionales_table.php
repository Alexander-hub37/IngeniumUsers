<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Docente;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_profesionales', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Docente::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('titulo');
            $table->string('institucion');
            $table->date('fecha_inicio');
            $table->date('fecha_termino');
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
        Schema::dropIfExists('datos_profesionales');
    }
};
