<?php

namespace App;

use App\Exceptions\ReservationNotFoundException;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    public function setAuthorIdAttribute($author)
    {
        if(is_int($author) && Author::find($author)){
            $this->attributes['author_id'] = $author;
        } else {
            $this->attributes['author_id'] = Author::firstOrCreate([
                'name' => $author,
            ])->id;
        }
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function checkout(User $user)
    {
        return $this->reservations()->create([
            'user_id' => $user->id,
            'checked_out_at' => now(),
        ]);
    }

    public function checkin(User $user)
    {
        $reservation = $this->reservations()->where('user_id', $user->id)
                ->whereNotNull('checked_out_at')
                ->whereNull('checked_in_at')
                ->first();
        
        if(is_null($reservation)){
            throw new ReservationNotFoundException('This book has not been checked out yet.');
        }

        return $reservation->update(['checked_in_at' => now()]);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
