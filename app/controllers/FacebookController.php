<?php

class FacebookController extends BaseController {

	protected $user;

	protected $fb;


	/**
	 * コンストラクタ
	 * @param Thread $thread
	 * @param Res $res
	 */
	public function __construct(User $user) {

		parent::__construct();

		// モデルインスタンスをセット
		$this->user = $user;

		// Facebook PHP SDK
		$this->fb = new Facebook(Config::get('facebook'));

	}


	/**
	 * Facebook login
	 */
	public function login() {

		$params = array(
			'redirect_uri' => url('/fb/loginCallback'),
			'scope' => 'user_photos',
		);

		return Redirect::to($this->fb->getLoginUrl($params));

	}


	/**
	 * Facebook login callback
	 */
	public function loginCallback() {

		$code = Input::get('code');
		if(strlen($code) == 0) {
			return Redirect::to('/')
				->with('error', 'Facebookとの接続でエラーが発生しました');
		}

		$fbid = $this->fb->getUser();

		if($fbid == 0) {
			return Redirect::to('/')
				->with('error', 'エラーが発生しました');
		}

		$me = $this->fb->api('/me');

		$user = $this->user->whereFbid($fbid)->first();
		if(empty($user)) {

			$user_data = [
				'fbid' => $me['id'],
				'first_name' => $me['first_name'],
				'last_name' => $me['last_name'],
				'name' => $me['name'],
				'gender' => $me['gender'],
				'photo' => 'https://graph.facebook.com/'.$me['id'].'/picture?type=large',
			];
			$user = $this->user->create($user_data);

		}

		Auth::login($user);

		return Redirect::to('/')
			->with('success', 'Facebookログインしました');

	}


	/**
	 * Facebookからアルバム一覧を取得
	 */
	public function getAlbums() {

		// Facebook パーミッション「user_photos」を確認
		$fb_user_photos_permission = false;

		$ret = $this->fb->api("/me/permissions");
		$permissions = $ret['data'];

		foreach($permissions as $permission) {
			if($permission['permission'] == 'user_photos' &&
			   $permission['status'] == 'granted') {
				$fb_user_photos_permission = true;
			}
		}

		// アルバムを取得
		$albums = [];
		if($fb_user_photos_permission) {

			$access_token = $this->fb->getAccessToken();

			$param = [
				'access_token' => $access_token,
			];
			$ret = $this->fb->api('/me/albums', 'GET', $param);
			$albums_tmp = $ret['data'];

			foreach($albums_tmp as $album) {

				$albums[] = [
					'id' => $album['id'],
					'name' => $album['name'],
					'count' => $album['count'],
				];

			}

		}

		$data = [
			'result' => [
				'fb_user_photos_permission' => $fb_user_photos_permission,
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


	/**
	 * Facebookパーミッション「user_photos」を許可する
	 */
	public function permitUserphotos() {

		$params = array(
			'redirect_uri' => url('/fb/permitUserphotosCallback'),
			'auth_type' => 'rerequest',
			'scope' => 'user_photos',
		);

		return Redirect::to($this->fb->getLoginUrl($params));

	}


	/**
	 * Facebookパーミッション「user_photos」許可後のコールバック
	 */
	public function permitUserphotosCallback() {

		$code = Input::get('code');
		if(strlen($code) == 0) {
			return Redirect::to('/')
				->with('error', 'Facebookとの接続でエラーが発生しました');
		}

		return Redirect::to('/')
			->with('success', 'Facebook写真の利用を許可しました');

	}

}
