<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Question as QuestionResource;
use App\Http\Resources\QuestionAnswer as QuestionAnswerResources;

class ReactionController extends BaseController
{
    /**
     * Manage User Reaction of Question & Answer
     *
     * @param string entity (question|answer)
     * @param int entity_id (question_id|answer_id)
     * @param string reaction (like|favorite)
     * @param string reaction_value (1|0|null)
     * @api /api/reactions/add
     * @method POST
     * @return json
     */
    public function manageReaction(Request $request)
    {
        // Validate Reaction Params
        $validator = Validator::make($request->all(), [
            'entity' => 'required',
            'entity_id' => 'required',
            'reaction' => 'required',
            'reaction_value' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        // get current user
        $user = auth()->user();
        $result = $increase = null;
        try {
            // select entity to react on
            switch ($request->get('entity')) {
                case 'question':
                    // get entity object (Here its a Question)
                    $entity = Question::where('id', $request->get('entity_id'))->first();
                    $result = new QuestionResource($entity);
                    break;

                case 'answer':
                    // get entity object (Here its an Answer)
                    $entity = QuestionAnswer::where('id', $request->get('entity_id'))->first();
                    $result = new QuestionAnswerResources($entity);
                    break;

                default:
                    return $this->sendError([], "reaction failed!");
                    break;
            }

            // check if entity exist
            if ($entity) {
                // update or create reaction
                $entity->reactions()->updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    $request->get('reaction') => $request->get('reaction_value')
                ]);

                $this->managePopularityCount($entity, $request->get('reaction_value'));

                return $this->sendResponse($result, "Reacted successfully!");
            } else {
                return $this->sendError([], "Entity not found!");
            }

        } catch (Exception $e) {
            return $this->sendError(null, $e->getMessage(), 400);
        }
    }

    private function managePopularityCount($entity, $increase = true)
    {
        $question = $entity->question ?? $entity;
        if ($increase === 1) {
            $question->increment('popularity');
        } else {
            $question->decrement('popularity');
        }
    }
}
