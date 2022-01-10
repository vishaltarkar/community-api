<?php

namespace App\Http\Controllers\API;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Question as QuestionResource;
use App\Http\Resources\QuestionPagination as QuestionPaginationResource;

class QuestionController extends BaseController
{
    /**
     * Get List of Questions
     * @method GET
     * @api api/questions
     * @param int pagination 0|1
     * @param int recent 0|1
     * @param int popular 0|1
     * @param int withAnswer 0|1
     * @return json
     */
    public function index(Request $request)
    {
        $questions = Question::with(['answers']);

        if ($request->get('recent', 0) == 1) {
            $questions->orderBy('created_at', 'DESC');
        }

        if ($request->get('popular') == 1) {
            $questions->orderBy('popularity', 'DESC');
        }

        // with pagination
        if ($request->get('pagination', 1) == 1) {
            $questions = $questions->paginate(10);
            $result = new QuestionPaginationResource($questions);
        } else {
            $questions = $questions->all();
            $result = QuestionResource::collection($questions);
        }
        return $this->sendResponse($result, 'Questions fetched.');
    }

    /**
     * Create a New Question
     * @method POST
     * @api /api/questions
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        $input['image'] = $imageName;

        $question = Question::create($input);
        return $this->sendResponse(new QuestionResource($question), 'Question created.');
    }

    /**
     * Get Question Details By Id
     * @method GET
     * @api /api/questions/:id
     * @param int $id
     * @return json
     */
    public function show($id)
    {
        $question = Question::find($id);
        if (is_null($question)) {
            return $this->sendError('Question does not exist.');
        }
        return $this->sendResponse(new QuestionResource($question), 'Question fetched.');
    }

    /**
     * Update Question Details By Id
     * @method PUT
     * @api /api/questions/:id
     * @param Request $request
     * @param Question $question
     * @return json
     */
    public function update(Request $request, Question $question)
    {
        // check if question owned by user or not
        if ($question->created_by != $user->id) {
            return $this->sendError([], 'permission denied', 403);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        if ($request->image) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $input['image'] = $imageName;
            if ($question->image && $question->image != 'image/' && $question->image != 'image/'.$request->image->getClientOriginalName()) {
                unlink(public_path($question->image)); // unlink old image
            }
        }
        $question->update($input);

        return $this->sendResponse(new QuestionResource($question), 'Question updated.');
    }

    /**
     * Delete Question
     * @method DELETE
     * @api /api/questions/:id
     * @param Question $question
     * @return json
     */
    public function destroy(Question $question)
    {
        $user = auth()->user();
        // check if question owned by user or not
        if ($question->created_by != $user->id) {
            return $this->sendError([], 'permission denied', 403);
        }

        // unlink image
        if (@$question->image && $question->image != 'image/') {
            unlink(public_path($question->image)); // unlink old image
        }

        $question->delete();
        return $this->sendResponse([], 'Question deleted.');
    }
}
