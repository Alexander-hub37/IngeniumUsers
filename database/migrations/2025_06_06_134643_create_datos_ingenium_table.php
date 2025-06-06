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
        Schema::create('datos_ingenium', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Docente::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('curso');
            $table->string('calificacion');
            $table->date('fecha_inicio');
            $table->date('fecha_termino');
            $table->string('estado');
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
        Schema::dropIfExists('datos_ingenium');
    }
};
