<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roleName = fake()->randomElement(['admin', 'user']);

        // Check if a role with this name already exists
        $existingRole = Role::where('name', $roleName)->first();

        if ($existingRole) {
            return [
                'name' => $existingRole->name,
            ];
        }

        return [
            'name' => $roleName,
        ];
    }
}
