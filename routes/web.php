<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/books', 'BookController@index')->name('books.index');
Route::get('/books/{book}', 'BookController@show')->name('books.show');
Route::post('/books', 'BookController@store')->name('books.store');
Route::patch('/books/{book}', 'BookController@update')->name('books.update');
Route::delete('/books/{book}', 'BookController@destroy')->name('books.destroy');

Route::get('/authors', 'AuthorController@index')->name('authors.index');
Route::post('/authors', 'AuthorController@store')->name('authors.store');
Route::get('/authors/create', 'AuthorController@create')->name('authors.create');

Route::post('/checkout/{book}', 'CheckoutBookController@store')->name('checkout');
Route::post('/checkin/{book}', 'CheckinBookController@store')->name('checkin');
