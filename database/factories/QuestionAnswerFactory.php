<?php

namespace Database\Factories;

use App\Models\QuestionAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuestionAnswer::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question_id' => random_int(1, 50),
            'answer' => $this->faker->paragraph(9),
            'created_by' => random_int(1, 10),
            'updated_by' => random_int(1, 10),
        ];
    }
}
