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
		'scope' => 'email,user_photos',
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
Route::when('thread*', 'auth');
Route::when('res*', 'auth');

// confirm thread
Route::post('/thread/confirm', function () {

	$inputs = Input::only('title', 'body', 'tmpimg_url', 'tmpimg_path', 'tmpimg_ext', 'user_id');

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

	$inputs = Input::only('title', 'body', 'tmpimg_path', 'tmpimg_ext', 'user_id');

	try {

		$thread_data = [
			'title' => $inputs['title'],
			'body' => $inputs['body'],
			'user_id' => $inputs['user_id'],
		];
		$thread = Thread::create($thread_data);

		// ファイルが指定されている、存在する場合、ファイルを一時ディレクトリから移動
		if($inputs['tmpimg_path'] && file_exists($inputs['tmpimg_path'])) {

			$file_url = '/assets/uploaded/' . $thread->id . '/';
			$file_dir = $_SERVER['DOCUMENT_ROOT'] . $file_url;
			if(!file_exists($file_dir)) {
				mkdir($file_dir);
			}
			$file_url = $file_url . '1.' . $inputs['tmpimg_ext'];
			$file_path = $file_dir . '1.' . $inputs['tmpimg_ext'];

			rename($inputs['tmpimg_path'], $file_path);

			$thread->uploaded_img = $file_url;
			$thread->save();

		}

		return Redirect::to('/detail/' . $thread->id)->with('success', 'スレッドを作成しました');

	} catch(Exception $e) {

		return Redirect::to('/')->with('error', 'スレッドの作成に失敗しました');

	}

});


// confirm res
Route::post('/res/confirm', function () {

	$inputs = Input::only('body', 'tmpimg_url', 'tmpimg_path', 'tmpimg_ext', 'thread_id', 'user_id');

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

	$inputs = Input::only('body', 'tmpimg_path', 'tmpimg_ext', 'thread_id', 'user_id');

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

		// ファイルが指定されている、存在する場合、ファイルを一時ディレクトリから移動
		if($inputs['tmpimg_path'] && file_exists($inputs['tmpimg_path'])) {

			$file_url = '/assets/uploaded/' . $thread->id . '/';
			$file_dir = $_SERVER['DOCUMENT_ROOT'] . $file_url;
			if(!file_exists($file_dir)) {
				mkdir($file_dir);
			}
			$file_url = $file_url . $res->res_no .'.' . $inputs['tmpimg_ext'];
			$file_path = $file_dir . $res->res_no . '.' . $inputs['tmpimg_ext'];

			rename($inputs['tmpimg_path'], $file_path);

			$res->uploaded_img = $file_url;
			$res->save();

		}

		// スレッド一覧で表示順位を上げるための更新
		$thread->updated_at = $res->created_at;
		$thread->save();

		return Redirect::to('/detail/' . $thread->id)->with('success', 'レスしました <a href="#res_' . $res->res_no . '">>> ' . $res->res_no . '</a>');

	} catch(Exception $e) {

		return Redirect::to('/detail/' . $thread->id)->with('error', 'レスに失敗しました');

	}

});


// get Facebook Albums
Route::post('fbalbums', function () {

	$facebook = new Facebook(Config::get('facebook'));

	//TODO:user_photosのパーミッションがなければここでも取得
	// $inputs = Input::only('code');
	// $url = 'http://fblogin.marutoto.com/fbalbum';
	// if(!$inputs['code']) {
	// 	$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" . Config::get('facebook.appId') . "&redirect_uri=" . urlencode($url) . '&scope=user_photos';
	// 	echo("<script> top.location.href='" . $dialog_url . "'</script>");
	// }

	$access_token = $facebook->getAccessToken();

	$param = [
		'access_token' => $access_token,
	];
	$ret = $facebook->api('/me/albums', 'GET', $param);
	$albums_tmp = $ret['data'];

	$albums = [];
	foreach($albums_tmp as $album) {

		$albums[] = [
			'id' => $album['id'],
			'name' => $album['name'],
			'count' => $album['count'],
		];

	}

	$data = [
		'result' => [
			'albums' => $albums,
		],
		'error' => 0,
	];

	echo json_encode($data);

});

// get Facebook Photos
Route::post('fbphotos', function () {

	$inputs = Input::only('album_id');

	$facebook = new Facebook(Config::get('facebook'));

	$ret = $facebook->api('/' . $inputs['album_id'] . '/photos', 'GET');
	$photos_tmp = $ret['data'];

	$photos = [];
	foreach($photos_tmp as $photo) {

		preg_match('/\/.*\?/', $photo['source'], $tmp);
		$tmp = explode('/', $tmp[0]);
		$tmp = end($tmp);
		$photo_name = str_replace('?', '', $tmp);
		$tmp = explode('.', $photo_name);
		$ext = $tmp[1];

		$photos[] = [
			'orig_url' => $photo['source'],
			'width' => $photo['width'],
			'height' => $photo['height'],
			'name' => $photo_name,
			'ext' => $ext,
		];

	}

	$data = [
		'result' => [
			'photos' => $photos,
		],
		'error' => 0,
	];

	echo json_encode($data);

});

// temporary upload Facebook Photo
Route::post('fbupload', function () {

	$inputs = Input::only('photo_orig_url', 'photo_name', 'photo_ext', 'tmpimg_path');

	// 既に画像がアップロードされている場合（2回目以降）はtmpimgを削除する
	if($inputs['tmpimg_path'] && file_exists($inputs['tmpimg_path'])) {
		unlink($inputs['tmpimg_path']);
	}

	$tmpimg_url = '/assets/uploaded/tmp/' . $inputs['photo_name'];
	$tmpimg_path = $_SERVER['DOCUMENT_ROOT'] . $tmpimg_url;

	$dl_img = file_get_contents($inputs['photo_orig_url']);
	file_put_contents($tmpimg_path, $dl_img);

	//TODO:暗号化
	$tmpimg_info = [
		'url' => $tmpimg_url,
		'path' => $tmpimg_path,
		'ext' => $inputs['photo_ext'],
	];

	$data = [
		'result' => [
			'tmpimg_info' => $tmpimg_info,
		],
		'error' => 0,
	];

	echo json_encode($data);

});





/*** エラーハンドリング ***/

// 404
App::missing(function($exception) {
    return Redirect::to('/')->with('error', '不正なアクセス、またはURLが間違っています');
});
