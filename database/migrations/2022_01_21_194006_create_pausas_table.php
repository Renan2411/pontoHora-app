<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePausasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pausas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ponto_id');
            $table->time('inicio');
            $table->time('fim');
            $table->time('total');
        
            $table->foreign('ponto_id')
                    ->references('id')
                    ->on('pontos')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

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
        Schema::dropIfExists('pausas');
    }
}
