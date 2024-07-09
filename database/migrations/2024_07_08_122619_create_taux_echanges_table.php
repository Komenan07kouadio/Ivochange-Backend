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
        Schema::create('taux_echanges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devise_source');
            $table->unsignedBigInteger('devise_cible');
            $table->decimal('taux', 10, 6);
            $table->timestamp('date_mise_a_jour')->useCurrent();
            $table->foreign('devise_source')->references('id')->on('devises');
            $table->foreign('devise_cible')->references('id')->on('devises');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taux_echanges');
    }
};
