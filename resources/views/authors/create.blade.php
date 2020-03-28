@extends('layouts.app')

@section('content')
    <div class="w-2/3 bg-gray-200 mx-auto p-6 shadow mt-10">
        <form action="{{ route('authors.store') }}" method="post" class="flex flex-col items-center">
            <h1 class="text-2xl">Add new Author</h1>
            @csrf
        <div class="pt-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Full Name
            </label>
            <input type="text" name="name" id="name" placeholder="full name" autocomplete="off"
             class="shadow appearance-none border rounded py-2 px-4 w-64 @error('name') border-red-500 @enderror">
            
            @error('name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Date of Birth
            </label>
            <input type="date" name="dob" id="dob" placeholder="date of birth" autocomplete="off"
             class="shadow appearance-none border rounded py-2 px-4 w-64 @error('dob') border-red-500 @enderror">
            
            @error('dob')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <button class="bg-blue-400 text-white rounded py-2 px-4" type="submit">Create Author</button>
        </div>
        </form>
    </div>
@endsection
