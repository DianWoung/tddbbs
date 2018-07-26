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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('threads','ThreadController@index');

Route::get('threads/create','ThreadController@create');
Route::get('threads/{channel}/{thread}','ThreadController@show');
Route::post('threads','ThreadController@store');
Route::get('threads/{channel}','ThreadController@index');
Route::post('/threads/{channel}/{thread}/replies','ReplyController@store')->name('threads.reply.store');
Route::get('/threads/{channel}/{thread}/replies','ReplyController@index')->name('threads.reply.index');
Route::delete('threads/{channel}/{thread}', 'ThreadController@destroy');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
//用户模型
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::patch('/replies/{reply}','ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy');