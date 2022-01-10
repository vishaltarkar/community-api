<?php

namespace Database\Seeders;

use App\Models\QuestionAnswer;
use Illuminate\Database\Seeder;

class ReactionAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // put reactions on question
        for ($i=0; $i < 500; $i++) {
            $answer_id = random_int(1, 500);
            $answer = QuestionAnswer::OfId($answer_id)->first();
            if ($answer) {
                if ($like = random_int(0,1) == 1) {
                    $answer->question()->increment('popularity');
                } else {
                    $answer->question()->decrement('popularity');
                }

                if ($favourite = random_int(0,1) == 1) {
                    $answer->question()->increment('popularity');
                } else {
                    $answer->question()->decrement('popularity');
                }

                $answer->reactions()->updateOrCreate([
                    'user_id' => random_int(1,10)
                ], [
                    'like' => $like,
                    'favourite' => $favourite
                ]);
            }
        }
    }
}
