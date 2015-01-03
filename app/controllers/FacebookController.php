<?php

class FacebookController extends BaseController {

	protected $thread;
	protected $res;

	protected $fb;


	/**
	 * コンストラクタ
	 * @param Thread $thread
	 * @param Res $res
	 */
	public function __construct(Thread $thread, Res $res) {

		parent::__construct();

		// モデルインスタンスをセット
		$this->thread = $thread;
		$this->res = $res;

		// Facebook PHP SDK
		$this->fb = new Facebook(Config::get('facebook'));

	}


	/**
	 * Facebook login
	 */
	public function login() {

		$params = array(
			'redirect_uri' => url('/fb/loginCallback'),
			'scope' => 'email,user_photos',
		);

		return Redirect::to($this->fb->getLoginUrl($params));

	}


	/**
	 * Facebook login callback
	 */
	public function loginCallback() {

		$code = Input::get('code');
		if(strlen($code) == 0) {
			return Redirect::to('/')->with('error', 'Facebookとの接続でエラーが発生しました');
		}

		$fbid = $this->fb->getUser();

		if($fbid == 0) {
			return Redirect::to('/')->with('error', 'エラーが発生しました');
		}

		$me = $this->fb->api('/me');

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

	}


	/**
	 * Facebookからアルバム一覧を取得
	 */
	public function getAlbums() {

		//TODO:user_photosのパーミッションがなければここでも取得
		// $inputs = Input::only('code');
		// $url = 'http://fblogin.marutoto.com/fbalbum';
		// if(!$inputs['code']) {
		// 	$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" . Config::get('facebook.appId') . "&redirect_uri=" . urlencode($url) . '&scope=user_photos';
		// 	echo("<script> top.location.href='" . $dialog_url . "'</script>");
		// }

		$access_token = $this->fb->getAccessToken();

		$param = [
			'access_token' => $access_token,
		];
		$ret = $this->fb->api('/me/albums', 'GET', $param);
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

	}


	/**
	 * Facebookから写真一覧を取得
	 */
	public function getPhotos() {

		$inputs = Input::only('album_id');

		$ret = $this->fb->api('/' . $inputs['album_id'] . '/photos', 'GET');
		$photos_tmp = $ret['data'];

		$photos = [];
		foreach($photos_tmp as $photo) {

			preg_match('/\/.*\?/', $photo['source'], $tmp);
			$tmp = explode('/', $tmp[0]);
			$tmp = end($tmp);
			$photo_name = str_replace('?', '', $tmp);
			$tmp = explode('.', $photo_name);
			$photo_ext = $tmp[1];

			$photos[] = [
				'orig_url' => $photo['source'],
				'width' => $photo['width'],
				'height' => $photo['height'],
				'name' => $photo_name,
				'ext' => $photo_ext,
			];

		}

		$data = [
			'result' => [
				'photos' => $photos,
			],
			'error' => 0,
		];

		echo json_encode($data);

	}


	/**
	 * 選択されたFacebook画像を一時ディレクトリへアップロード
	 */
	public function uploadPhoto() {

		$inputs = Input::only('photo_orig_url', 'photo_name', 'photo_ext', 'tmpimg_path');

		// 既に画像がアップロードされている場合（2回目以降）はtmpimgを削除する
		if($inputs['tmpimg_path'] && file_exists($inputs['tmpimg_path'])) {
			unlink($inputs['tmpimg_path']);
		}

		$tmpimg_url = '/assets/uploaded/tmp/' . $inputs['photo_name'];
		$tmpimg_path = $_SERVER['DOCUMENT_ROOT'] . $tmpimg_url;

		$dl_img = file_get_contents($inputs['photo_orig_url']);
		file_put_contents($tmpimg_path, $dl_img);

		// 画像の一時アップロードパスを暗号化
		$tmpimg_path_crypted = Crypt::encrypt($tmpimg_path);

		$tmpimg_info = [
			'url' => $tmpimg_url,
			'path' => $tmpimg_path_crypted,
			'ext' => $inputs['photo_ext'],
		];

		$data = [
			'result' => [
				'tmpimg_info' => $tmpimg_info,
			],
			'error' => 0,
		];

		echo json_encode($data);

	}

}
