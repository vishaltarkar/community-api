<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReactionQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // put reactions on question
        for ($i=0; $i < 50; $i++) {
            $question_id = random_int(1, 50);
            $question = Question::OfId($question_id)->first();
            if ($question) {

                if ($like = random_int(0,1) == 1) {
                    $question->increment('popularity');
                } else {
                    $question->decrement('popularity');
                }

                if ($favourite = random_int(0,1) == 1) {
                    $question->increment('popularity');
                } else {
                    $question->decrement('popularity');
                }

                $question->reactions()->updateOrCreate([
                    'user_id' => random_int(1,10)
                ], [
                    'like' => $like,
                    'favourite' => $favourite
                ]);
            }
        }
    }
}
