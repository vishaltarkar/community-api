<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResources;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionAnswer extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'answer' => $this->answer,
            'question_id' => $this->question_id,
            'created_at' => $this->created_at->format('m/d/Y'),
            'updated_at' => $this->updated_at->format('m/d/Y'),
            'like' => $this->like,
            'favourite' => $this->favourite,
            'created_by' => new UserResources($this->createdBy),
            'updated_by' => new UserResources($this->createdBy)
        ];
    }
}
