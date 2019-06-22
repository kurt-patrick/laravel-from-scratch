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

/*
// example with params
Route::get('/users/{id}/{name}', function ($id, $name) {
    return 'hello ' . $name . ' your id is ' . $id;
});

// this will go directly to a page which typically isnt what you want to do
// instead, go through the controller as demonstrated below
Route::get('/about', function () {
    return view('pages/about');
});
*/


Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', 'PagesController@services');

// rather than create a route for all CRUD functioanlity
// this one command will create routes for all PostsController routes
// this can be confirmed in the terminal with: php artisan route:list
Route::resource('posts', 'PostsController');
//Route::post('/store', 'PostsController@store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
