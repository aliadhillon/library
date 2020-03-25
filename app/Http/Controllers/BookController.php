<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\PostRequest;

class BookController extends Controller
{
    public function store(PostRequest $request)
    {
        Book::create($request->validated());
    }

    public function update(PostRequest $request, Book $book)
    {
        $book->update($request->validated());
    }
}
