<?php

namespace App\Http\Resources;

use App\Http\Resources\Question as QuestionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionPagination extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'questions' => QuestionResource::collection($this->collection),
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
