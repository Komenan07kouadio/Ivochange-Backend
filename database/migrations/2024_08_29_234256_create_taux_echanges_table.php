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
            $table->id();  // Clé primaire
            $table->unsignedBigInteger('devise_id');  // Clé étrangère vers la table currencies
            $table->decimal('taux', 10, 6);  // Taux de change
            $table->date('date_taux');  // Date du taux
            $table->boolean('isActive')->default(true);
            $table->boolean('isDelete')->default(false);
            $table->timestamps();  // Colonnes created_at et updated_at

            // Définition de la clé étrangère
            $table->foreign('devise_id')->references('id')->on('devises')->onDelete('cascade');
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
