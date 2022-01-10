<?php

namespace App\Http\Resources;

use App\Http\Resources\QuestionAnswer as QuestionAnswerResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionAnswerPagination extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'answers' => QuestionAnswer::collection($this->collection),
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage()
            ]
        ];
    }
}
