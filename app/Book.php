<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    public function setAuthorIdAttribute($authorName)
    {
        $author = Author::firstOrCreate([
            'name' => $authorName,
        ]);

        $this->attributes['author_id'] = $author->id;
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
