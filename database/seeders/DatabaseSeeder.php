<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Database\Seeder;
use Database\Seeders\ReactionQuestionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if ($this->command->confirm('Do you want run the seeder of Luca-App?')) {
            User::factory(10)->create();
            Question::factory(50)->create();
            QuestionAnswer::factory(500)->create();
            $this->call(ReactionQuestionSeeder::class);
            $this->call(ReactionAnswerSeeder::class);
        }
    }
}
