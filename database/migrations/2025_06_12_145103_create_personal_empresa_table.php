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
        Schema::create('personal_empresa', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('telefono');
            $table->string('nombres');
            $table->string('dni', 20)->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('direccion')->nullable();
            $table->string('foto')->nullable(); 
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
        Schema::dropIfExists('personal_empresa');
    }
};
