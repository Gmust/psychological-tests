<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->create()
            ->each(function ($user) {
                $testIds = Test::factory()->count(5)->create()->pluck('id')->toArray();
                $user->update(['passed_tests_ids' => json_encode($testIds, JSON_THROW_ON_ERROR)]);
            });
    }
}
