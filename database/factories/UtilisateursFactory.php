<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Utilisateurs>
 */
class UtilisateursFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName,
            'prenoms' => $this->faker->firstName,
            'telephone' => $this->faker->phoneNumber,
            'pays_resident' => $this->faker->country,
            'email' => $this->faker->unique()->safeEmail(),
            'mot_de_passe' => bcrypt('password'), // Mot de passe par défaut
            'date_inscription' => now(),
            'statut' => 'actif', // Tu peux adapter ce champ selon tes besoins
        ];
    }
}
