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

Route::get('/register/confirm','Auth\RegisterConfirmationController@index')->name('register.confirm');
//文章模块
Route::get('threads','ThreadController@index')->name('threads');

Route::get('threads/create','ThreadController@create');
Route::get('threads/{channel}/{thread}','ThreadController@show');
Route::post('threads','ThreadController@store')->middleware('must_be_confirmed');
Route::get('threads/{channel}','ThreadController@index');
Route::post('/threads/{channel}/{thread}/replies','ReplyController@store')->name('threads.reply.store');
Route::get('/threads/{channel}/{thread}/replies','ReplyController@index')->name('threads.reply.index');
Route::delete('threads/{channel}/{thread}', 'ThreadController@destroy');


Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

//评论模块
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');

Route::patch('/replies/{reply}','ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy');

//用户模块
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications','UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}','UserNotificationsController@destroy');
//订阅模块
Route::post('/threads/{channel}/{thread}/subscriptions','ThreadSubscriptionsController@store')
        ->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions','ThreadSubscriptionsController@destroy')
        ->middleware('auth');
//用户列表
Route::get('/api/users', 'Api\UsersController@index');
//用户头像
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')
    ->middleware('auth')->name('avatar');

//最佳评论
Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');
Route::delete('/replies/{reply}','ReplyController@destroy')->name('replies.destroy');