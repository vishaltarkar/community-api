<?php

namespace App\Http\Resources;
use App\Http\Resources\User as UserResources;
use Illuminate\Http\Resources\Json\JsonResource;

class Question extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'like' => $this->like,
            'favourite' => $this->favourite,
            'image' => asset($this->image),
            'created_at' => $this->created_at->format('m/d/Y'),
            'updated_at' => $this->updated_at->format('m/d/Y'),
            'created_by' => new UserResources($this->createdBy),
            'updated_by' => new UserResources($this->createdBy)
        ];
    }

}
