<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*** guest routes ***/

// list thread
Route::get('/', 'ThreadController@index');

// show thread detail
Route::get('/detail/{thread_id?}', 'ThreadController@detail');


// Facebook Login
Route::get('/fb/login', 'FacebookController@login');

// Facebook Login Callback（FacebookAPI経由後のコールバック先）
Route::get('/fb/loginCallback', 'FacebookController@loginCallback');


// logout
Route::get('/logout', function() {

	Auth::logout();
	return Redirect::to('/')->with('success', 'ログアウトしました');

});



/*** Auth routes ***/

// define Auth filter
// Route::when('thread*', 'auth');
// Route::when('res*', 'auth');

// confirm thread
Route::post('/thread/confirm', 'RegisterController@confirmThread');

// save thread
Route::post('/thread/save', 'RegisterController@saveThread');

// confirm res
Route::post('/res/confirm', 'RegisterController@confirmRes');

// save res
Route::post('/res/save', 'RegisterController@saveRes');


// get Facebook Albums
Route::post('/fb/albums', 'FacebookController@getAlbums');

// get Facebook Photos
Route::post('/fb/photos', 'FacebookController@getPhotos');

// temporary upload Facebook Photo
Route::post('/fb/uploadPhoto', 'FacebookController@uploadPhoto');

// permit Facebook user_photos
Route::get('/fb/permitUserphotos', 'FacebookController@permitUserphotos');

// permit Facebook user_photos Callback（FacebookAPI経由後のコールバック先）
Route::get('/fb/permitUserphotosCallback', 'FacebookController@permitUserphotosCallback');




/*** エラーハンドリング ***/

App::error(function (Exception $exception) {
	return Redirect::to('/')
		->with('error', '不正なアクセス、またはURLが間違っています');
});
