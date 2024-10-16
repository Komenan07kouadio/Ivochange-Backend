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
        /*Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utilisateur_id');
            $table->foreignId('devise_envoyee_id')->constrained('devises')->onDelete('cascade')->onUpdate('cascade'); // Clé étrangère vers la table devises pour la devise envoyée
            $table->foreignId('devise_recue_id')->constrained('devises')->onDelete('cascade')->onUpdate('cascade'); // Clé étrangère vers la table devises pour la devise reçue
            $table->decimal('montant_envoye', 10, 2)->nullable();
            $table->string('numero_compte_envoye')->nullable();
            $table->decimal('montant_reçu', 10, 2)->nullable();
            $table->string('numero_compte_reçu')->nullable();
            $table->decimal('montant_frais_inclus_envoye', 10, 2)->nullable();
            $table->decimal('montant_frais_inclus_reçu', 10, 2)->nullable();
            $table->dateTime('date_transaction')->useCurrent();
            $table->enum('statut', ['attente', 'approuve', 'annule'])->default('attente');
            $table->boolean('isActive')->default(true);
            $table->boolean('isDelete')->default(false);
            $table->timestamps();

            // Définir explicitement les noms des clés étrangères
            $table->foreign('utilisateur_id')
                ->references('id')->on('utilisateurs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });*/
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Utilisateur clé étrangère
            $table->unsignedBigInteger('utilisateur_id');
            $table->foreign('utilisateur_id')
            ->references('id')->on('utilisateurs')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            // Devise envoyée clé étrangère
            $table->unsignedBigInteger('devise_envoyee_id');
            $table->foreign('devise_envoyee_id')
            ->references('devise_id')->on('devises')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            // Devise reçue clé étrangère
            $table->unsignedBigInteger('devise_recue_id');
            $table->foreign('devise_recue_id')
            ->references('devise_id')->on('devises')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            // Autres colonnes
            $table->decimal('montant_envoye', 10, 2)->nullable();
            $table->string('numero_compte_envoye')->nullable();
            $table->decimal('montant_reçu', 10, 2)->nullable();
            $table->string('numero_compte_reçu')->nullable();
            $table->decimal('montant_frais_inclus_envoye', 10, 2)->nullable();
            $table->decimal('montant_frais_inclus_reçu', 10, 2)->nullable();
            $table->dateTime('date_transaction')->useCurrent();
            $table->enum('statut', ['attente', 'approuve', 'annule'])->default('attente');
            $table->boolean('isActive')->default(true);
            $table->boolean('isDelete')->default(false);
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
