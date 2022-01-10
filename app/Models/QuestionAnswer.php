<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionAnswer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer',
        'created_by',
        'updated_by'
    ];

    protected $appends = [
        'like', 'favourite'
    ];


    # Scopes
    public function scopeOfQuestionId($query, $question_id)
    {
        return $query->where('question_id', $question_id);
    }

    # Relationships
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    # Accessors
    public function getLikeAttribute()
    {
        if ($user = auth()->user()) {
            return $this->reactions()->OfUserId($user->id)->pluck('like')->first() ?? null;
        }
        return null;
    }

    public function getFavouriteAttribute()
    {
        if ($user = auth()->user()) {
            return $this->reactions()->OfUserId($user->id)->pluck('favourite')->first() ?? null;
        }
        return null;
    }

}
