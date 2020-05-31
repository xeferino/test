<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('productos_id')->nullable($value=false);
            $table->string('numero_guia')->unique()->nullable($value=true);
            $table->string('descripcion')->nullable($value=false);
            $table->foreign('productos_id')
            ->references('id')
            ->on('productos')
            ->onDelete('cascade');
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
        Schema::dropIfExists('guias');
    }
}
