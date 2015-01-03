<?php

class BaseController extends Controller {

	/**
	 * コンストラクタ
	 */
	public function __construct() {

		// ログイン認証チェック
		if(Auth::check()) {
			View::share('me', Auth::user());
		}

		// ドメインを除いたURLセグメントを取得する
		//    $url_tmp [0]=> string(5) "http:" [1]=> string(0) "" [2]=> string(20) "fblogin.marutoto.com"
		$url = url();
		$url_tmp = explode('/', $url);
		unset($url_tmp[0]);
		unset($url_tmp[1]);
		unset($url_tmp[2]);

		$doc_root = '/';
		foreach($url_tmp as $url_segment) $doc_root .= $url_segment . '/';

		// jsへドキュメントルートを渡す（環境ごとのURLに依存しない為）
		//    http://fblogin.marutoto.com/
		//    http://locahost:8081/fblogin/  どちらにも対応可
		View::share('doc_root', $doc_root);

	}


	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
