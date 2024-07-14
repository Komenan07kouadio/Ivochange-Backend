<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTauxEchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taux_echanges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devise_source');
            $table->unsignedBigInteger('devise_cible');
            $table->foreign('devise_source')->references('id')->on('devises')->onDelete('cascade');
            $table->foreign('devise_cible')->references('id')->on('devises')->onDelete('cascade');
            $table->decimal('taux', 8, 4);
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
        Schema::dropIfExists('taux_echanges');
    }
}
