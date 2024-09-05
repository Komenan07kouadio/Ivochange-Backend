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
            $table->decimal('montant_envoye', 10, 2)->nullable();
            $table->string('numero_compte_envoye')->nullable(); // Peut-être nullable si pas toujours utilisé
            $table->decimal('montant_reçu', 10, 2)->nullable();
            $table->string('numero_compte_reçu')->nullable(); // Peut-être nullable si pas toujours utilisé
            $table->unsignedBigInteger('devise_id');
            $table->decimal('montant_frais_inclus_envoye', 10, 2)->nullable();
            $table->decimal('montant_frais_inclus_reçu', 10, 2)->nullable();
            $table->dateTime('date_transaction')->useCurrent(); // Utilisation de l'heure actuelle par défaut
            $table->enum('statut', ['attente', 'approuve', 'annule'])->default('attente');
            
            $table->boolean('isActive')->default(true);
            $table->boolean('isDelete')->default(false);
            $table->timestamps();

            // Clés étrangères
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('devise_id')->references('id')->on('devises')->onDelete('cascade');
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
