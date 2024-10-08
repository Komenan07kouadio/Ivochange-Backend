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
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('telephone');
            $table->string('pays');
            $table->date('date_inscription');
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->timestamp('date_creation')->useCurrent();
            $table->enum('statut', ['actif', 'inactif', 'banni'])->default('actif');
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
        Schema::dropIfExists('utilisateurs');
    }
};
