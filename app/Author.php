<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];

    protected $dates = [
        'dob'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
