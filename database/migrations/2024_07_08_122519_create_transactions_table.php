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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utilisateur_id');
            $table->unsignedBigInteger('portefeuille_id');
            $table->decimal('montant_envoye', 10, 2)->nullable();
            $table->decimal('montant_reçu', 10, 2)->nullable();
            $table->decimal('montant', 10, 2);
            $table->unsignedBigInteger('devise_id');
            $table->enum('type', ['achat', 'vente']);
            $table->timestamp('date')->useCurrent();
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');
            $table->foreign('portefeuille_id')->references('id')->on('portefeuilles');
            $table->foreign('devise_id')->references('id')->on('devises');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
