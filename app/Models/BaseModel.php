<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                if (auth()->user()) {
                    $model->created_by = auth()->user()->id ?? null;
                }
            }
        );

        static::updating(
            function ($model) {
                if (auth()->user()) {
                    $model->updated_by = auth()->user()->id ?? null;
                }
            }
        );
    }

    public function scopeOfId($query, $id)
    {
        return $query->where('id', $id);
    }
}
