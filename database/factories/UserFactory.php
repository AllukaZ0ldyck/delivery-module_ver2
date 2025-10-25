<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname'         => $this->faker->firstName,
            'lastname'          => $this->faker->lastName,
            'name'              => fn (array $attrs) => $attrs['firstname'].' '.$attrs['lastname'],
            'email'             => $this->faker->unique()->safeEmail(),
            'contact'           => $this->faker->numerify('09#########'),
            'address'           => $this->faker->address(),
            'gallon_type'       => $this->faker->randomElement(['Blue 5 Gallon', 'Slim 5 Gallon']),
            'gallon_count'      => $this->faker->numberBetween(1, 5),
            'role'              => 'customer',
            'approval_status'   => 'approved',
            'qr_token'          => (string) Str::uuid(),
            'confirmation_code' => strtoupper(Str::random(10)),
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
