@extends('layouts.app')

@section('content')
    <div class="w-2/3 bg-gray-200 mx-auto p-6 shadow mt-10 flex flex-col items-center">
        <h1 class="text-black-50 text-2xl">Authors</h1>
        @if (session()->has('msg'))
                <p class="text-green-500">{{ session('msg') }}</p>
            @endif
        <table class="table-auto">
        <thead>
            <tr>
            <th class="px-4 py-2">Id</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Date of Birth</th>
            </tr>
        </thead>
        <tbody>
             @foreach ($authors as $author)
                <tr>
                    <td class="border px-4 py-2">{{ $author->id }}</td>
                    <td class="border px-4 py-2">{{ $author->name }}</td>
                    <td class="border px-4 py-2">{{ $author->dob->toFormattedDateString() }}</td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
@endsection
