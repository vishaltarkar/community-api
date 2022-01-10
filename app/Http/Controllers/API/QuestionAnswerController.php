<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\QuestionAnswer as QuestionAnswerResource;
use App\Http\Resources\QuestionAnswerPagination as QuestionAnswerPaginationResource;

class QuestionAnswerController extends BaseController
{
    /**
     * Get List of Question's Answer
     * @method GET
     * @api api/question/id/answers
     * @return json
     */
    public function index(Request $request, $question_id)
    {
        if ($request->get('pagination', 1) == 1) {
            $questions = QuestionAnswer::paginate(10);
            $result = new QuestionAnswerPaginationResource($questions);
        } else {
            $questions = QuestionAnswer::all();
            $result = QuestionAnswerResource::collection($questions);
        }
        return $this->sendResponse($result, 'Question`s answers fetched.');
    }

    /**
     * Create a New Question
     * @method POST
     * @api /api/question/:id/answers
     * @param Request $request
     * @return json
     */
    public function store(Request $request, $question_id)
    {
        $input = array_merge($request->all(), compact('question_id'));
        $validator = Validator::make($input, [
            'answer' => 'required',
            'question_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        try {

            // on answer increase the popularity
            $question = Question::OfId($question_id);
            $question->increment('popularity');

            $questionAnswer = QuestionAnswer::create($input);
            return $this->sendResponse(new QuestionAnswerResource($questionAnswer), 'Question`s answers created.');
        } catch (Exception $e) {
            return $this->sendError(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get Question Details By Id
     * @method GET
     * @api /api/question/answers/:id
     * @param int $id
     * @return json
     */
    public function show($id)
    {
        $question = QuestionAnswer::find($id);
        if (is_null($question)) {
            return $this->sendError('Question does not exist.');
        }
        return $this->sendResponse(new QuestionAnswerResource($question), 'Question`s answers fetched.');
    }

    /**
     * Update Question's Answer Details By Id
     * @method PUT
     * @api /api/question/answers/:id
     * @param Request $request
     * @param QuestionAnswer $answer
     * @return json
     */
    public function update(Request $request, QuestionAnswer $answer)
    {
        // check if user has permission
        $user = auth()->user();
        if ($answer->created_by != @$user->id) {
            return $this->sendError([], 'permission denied', 403);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $answer->answer = $input['answer'];
        $answer->save();

        $answer = $answer->fresh();

        return $this->sendResponse(new QuestionAnswerResource($answer), 'Question`s answers updated.');
    }

    /**
     * Delete Question's Answer
     * @method DELETE
     * @api /api/question/answers/:id
     * @param QuestionAnswer $answer
     * @return json
     */
    public function destroy(QuestionAnswer $answer)
    {
        try {

            $user = auth()->user();
            if ($answer->created_by != @$user->id) {
                return $this->sendError([], 'permission denied', 403);
            }

            // on answer decrease the popularity
            Question::OfId($answer->question_id)->decrement('popularity');
            $answer->delete();
            return $this->sendResponse([], 'Question`s answers deleted.');
        } catch (Exception $e) {
            return $this->sendError(null, $e->getMessage(), $e->getCode());
        }
    }
}
