<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'popularity',
        'created_by',
        'updated_by'
    ];

    protected $appends = [
        'like', 'favourite'
    ];

    # Scope
    public function scopeOfCreatorId($query, $id)
    {
        return $query->where('created_by', $id);
    }

    # relationships
    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class, 'question_id', 'id');
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

    #mutators
    public function setImageAttribute($value) {
        if ($value) {
            $value = 'images/'. $value;
        } else {
            $value = null;
        }
        $this->attributes['image'] = $value;
    }

}
