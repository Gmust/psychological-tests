<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use Database\Factories\QuestionFactory;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Test::factory()
            ->count(10)
            ->has(Question::factory()->count(10)->has(Answer::factory()->count(4)))
            ->create();

        Test::factory()
            ->count(4)
            ->has(Question::factory()->count(4)->has(Answer::factory()->count(2)))
            ->create();
    }
}
