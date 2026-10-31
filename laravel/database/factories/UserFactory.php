<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->optional()->e164PhoneNumber(),
            'password_hash' => 'password', // hashed by the model cast
            'role' => 'user',
            'status' => 'active',
            'gdpr_accepted' => true,
            'gdpr_accepted_at' => now(),
        ];
    }

    public function librarian(): static
    {
        return $this->state(fn () => ['role' => 'librarian']);
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }
}
