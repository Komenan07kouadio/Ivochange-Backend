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
        Schema::create('portefeuilles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utilisateur_id');
            $table->unsignedBigInteger('devise_id');
            $table->decimal('solde', 10, 2)->default(0.00);
            $table->timestamps();

            // Définition des clés étrangères
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');
            $table->foreign('devise_id')->references('id')->on('devises');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portefeuilles');
    }
};
