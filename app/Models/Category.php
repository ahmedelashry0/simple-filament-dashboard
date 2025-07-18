<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Get the posts associated with the category.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
