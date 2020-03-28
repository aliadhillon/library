<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{
    public function index()
    {
        return view('authors.index', ['authors' => Author::all()]);
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(AuthorRequest $request)
    {
        $author = Author::create($request->validated());

        return redirect()->route('authors.index')->with('msg', "{$author->name} has been created.");
    }
}
