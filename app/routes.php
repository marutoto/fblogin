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

	$view_data = [];

	$threads = Thread::with(['ress' => function($q) {
		$q->orderBy('res_no', 'desc');
	}])
		->orderBy('updated_at', 'desc')
		->orderBy('id', 'desc')
		->take(10)
		->get();

	$ress = [];
	foreach($threads as $thread) {
		// $ress_desc = $thread->ress->take(10)->toArray();
		// $ress[$thread->id] = array_reverse($ress_desc);
		//TODO:reverse
		$ress[$thread->id] = $thread->ress->take(10);
	}

	$view_data['threads'] = $threads;
	$view_data['ress'] = $ress;
	return View::make('list', $view_data);

});


// show thread detail
Route::get('/detail/{thread_id?}', function($thread_id) {

	$thread = Thread::find($thread_id);
	if(!$thread) {
		Redirect::to('/')->with('error', 'スレッドがありません');;
	}

	$view_data['thread'] = $thread;
	return View::make('detail', $view_data);

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
	if(strlen($code) == 0) return Redirect::to('/')->with('error', 'Facebookとの接続でエラーが発生しました。');

	$facebook = new Facebook(Config::get('facebook'));
	$fbid = $facebook->getUser();

	if($fbid == 0) return Redirect::to('/')->with('error', 'エラーが発生しました。');

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

	return Redirect::to('/')->with('success', 'Facebookログインしました');

});


// logout
Route::get('logout', function() {

	Auth::logout();
	return Redirect::to('/')->with('success', 'ログアウトしました');

});



/*** Auth routes ***/

// define Auth filter
Route::when( 'thread*', 'auth' );

// confirm thread
Route::post('/thread/confirm', function () {

	$inputs = Input::only('title', 'body', 'user_id');

	$validator = Validator::make($inputs,[
		'title' => 'required',
		'body' => 'required',
	]);

	if($validator->fails()) {
		return Redirect::to('/#thread-form')
			->withErrors($validator)
			->withInput();
	}

	$view_data['inputs'] = $inputs;
	return View::make('confirm_thread', $view_data);

});

// save thread
Route::post('/thread/save', function () {

	$inputs = Input::only('title', 'body', 'user_id');

	try {

		$thread = Thread::create($inputs);
		return Redirect::to('/detail/' . $thread->id)->with('success', 'スレッドを作成しました');

	} catch(Exception $e) {

		return Redirect::to('/')->with('error', 'スレッドの作成に失敗しました');

	}

});


// confirm res
Route::post('/res/confirm', function () {

	$inputs = Input::only('body', 'thread_id', 'user_id');

	$thread = Thread::find($inputs['thread_id']);
	if(!$thread) {
		return Redirect::to('/')->with('error', 'スレッドが存在しません');
	}

	$validator = Validator::make($inputs,[
		'body' => 'required',
	]);

	if($validator->fails()) {
		return Redirect::to('/detail/' . $thread->id . '/#res-form')
			->withErrors($validator)
			->withInput();
	}

	$view_data['inputs'] = $inputs;
	return View::make('confirm_res', $view_data);

});

// save res
Route::post('/res/save', function () {

	$inputs = Input::only('body', 'thread_id', 'user_id');

	$thread = Thread::find($inputs['thread_id']);
	if(!$thread) {
		return Redirect::to('/')->with('error', 'スレッドが存在しません');
	}

	$res_last = Res::where('thread_id', $thread->id)
		->orderBy('res_no', 'desc')
		->first();
	if($res_last) {
		$inputs['res_no'] = $res_last->res_no + 1;
	} else {
		$inputs['res_no'] = 2;
	}

	try {

		$res = Res::create($inputs);
		$thread->updated_at = $res->created_at;
		$thread->save(); // スレッド一覧で表示順位を上げるための更新
		return Redirect::to('/detail/' . $thread->id)->with('success', 'レスしました <a href="#res_' . $res->res_no . '">>> ' . $res->res_no . '</a>');

	} catch(Exception $e) {

		return Redirect::to('/detail/' . $thread->id)->with('error', 'レスに失敗しました');

	}

});


/*** エラーハンドリング ***/

// 404
App::missing(function($exception) {
    return Redirect::to('/')->with('error', '不正なアクセス、またはURLが間違っています');
});
