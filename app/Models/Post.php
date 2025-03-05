<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // scopes
    public function scopeIsNotDraft($query)
    {
        return $query->where('is_draft', false);
    }

    public function scopeIsDraft($query)
    {
        return $query->where('is_draft', true);
    }

    public function scopePublished($query)
    {
        return $query->where('publish_date', '<=', now());
    }
}
