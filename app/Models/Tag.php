<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Get the posts associated with the tag.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }
}
