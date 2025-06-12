<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas_citas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios_disponibles')->onDelete('cascade');
            $table->string('estado')->default('pendiente'); 
            $table->text('motivo')->nullable();
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
        Schema::dropIfExists('reservas_citas');
    }
};
