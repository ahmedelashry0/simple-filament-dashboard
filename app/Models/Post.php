<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['title', 'content', 'category_id' , 'slug', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Get the category associated with the post.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tags associated with the post.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }
}
