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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', 'UserController@login');
Route::post('/login/check', 'UserController@checkLogin');
Route::get('/movies', 'MovieController@getGenreList');
Route::get('/logout', 'UserController@logout');
Route::post('/get-genre-movies', 'MovieController@getGenreMovies');
Route::post('/get-searched-movies', 'MovieController@getSearchedMovies');
Route::post('/check-session', 'UserController@checkSession');
Route::post('/get-movie', 'MovieController@getMovie');
