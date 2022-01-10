<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reaction extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'like',
        'favourite'
    ];

    // Scope
    public function scopeOfUserId($query, $user_id) {
        return $query->where('user_id', $user_id);
    }

    // relationships
    public function reactionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
