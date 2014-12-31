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
Route::get('/', function() {

	return View::make('list');

});


// show thread detail
Route::get('/detail', function() {

	return View::make('detail');

});


// Facebook Login
Route::get('/login/fb', function() {

	$facebook = new Facebook(Config::get('facebook'));

	$params = array(
		'redirect_uri' => url('/login/fb/callback'),
		'scope' => 'email',
	);

	return Redirect::to($facebook->getLoginUrl($params));

});


// Facebook Login Callback（FacebookAPI経由後のコールバック先）
Route::get('login/fb/callback', function() {

	$code = Input::get('code');
	if(strlen($code) == 0) return Redirect::to('/')->with('message', 'Facebookとの接続でエラーが発生しました。');

	$facebook = new Facebook(Config::get('facebook'));
	$fbid = $facebook->getUser();

	if($fbid == 0) return Redirect::to('/')->with('message', 'エラーが発生しました。');

	$me = $facebook->api('/me');

	$user = User::whereFbid($fbid)->first();
	if(empty($user)) {

		$user = new User;
		$user->fbid = $me['id'];
		$user->first_name = $me['first_name'];
		$user->last_name = $me['last_name'];
		$user->name = $me['name'];
		$user->gender = $me['gender'];
		$user->photo = 'https://graph.facebook.com/'.$me['id'].'/picture?type=large';

		$user->save();

	}

	Auth::login($user);

	return Redirect::to('/')->with('message', 'Facebookログインしました');

});


// logout
Route::get('logout', function() {
	Auth::logout();
	return Redirect::to('/')->with('message', 'ログアウトしました');
});



/*** Auth routes ***/

// define Auth filter
Route::when( 'thread*', 'auth' );

// create thread
Route::post('/thread/create', function () {

	echo 'create thread';
	exit;

});

// post thread response
Route::post('/thread/response', function () {

	echo 'response';
	exit;

});
