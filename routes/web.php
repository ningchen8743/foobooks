<?php

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


Route::get('/', 'WelcomeController');

/*Route::get('/books/{category}/{title}', function ($category, $title) {
    return 'You are viewing the book:' .$title.' in the category '.$category;
});*/
Route::get('/books/search', 'BookController@search'); # <-- NEW 1 of 2
Route::get('/books/search-process', 'BookController@searchProcess'); # <-- NEW 2 of 2

Route::get('/books/create', 'BookController@create');
Route::post('/books', 'BookController@store');

Route::get('/books/{title}', 'BookController@show');
Route::get('/books', 'BookController@index');

Route::any('/practice/{n?}', 'PracticeController@index');

Route::view('/about', 'about');
Route::view('/contact', 'contact');


