<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\PostRequest;

class BookController extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function show(Book $book)
    {
        return $book;
    }

    public function store(PostRequest $request)
    {
        $book = Book::create($request->validated());

        return redirect()->route('books.show', ['book' => $book]);
    }

    public function update(PostRequest $request, Book $book)
    {
        $book->update($request->validated());

        return redirect()->route('books.show', ['book' => $book]);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index');
    }
}
