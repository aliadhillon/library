<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{
    public function store(AuthorRequest $request)
    {
        Author::create($request->validated());
    }
}
