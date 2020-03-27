<?php

namespace App\Http\Controllers;

use App\Book;
use App\Exceptions\ReservationNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinBookController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function store(Book $book)
    {
        try {
            $book->checkin(Auth::user());
        } catch (ReservationNotFoundException $e) {
            abort(404, $e->getMessage());
        }
    }
}
