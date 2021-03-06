<?php

class RegisterController extends BaseController {

	protected $thread;
	protected $res;


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

	}


	/**
	 * スレッド作成確認
	 */
	public function confirmThread() {

		$inputs = Input::only('title_thread', 'body_thread', 'tmpimg_url_thread', 'tmpimg_path_thread', 'tmpimg_ext_thread');

		$view_data['inputs'] = $inputs;
		return View::make('confirm_thread', $view_data);

	}


	/**
	 * スレッド作成
	 */
	public function saveThread() {

		$inputs = Input::only('title_thread', 'body_thread', 'tmpimg_path_thread', 'tmpimg_ext_thread');

		// 「戻る」押下時
		if(Input::get('_return')) {
			return Redirect::to('/#thread-form')
				->withInput();
		}

		$inputs_tmp = [
			'title' => $inputs['title_thread'],
			'body' => $inputs['body_thread'],
		];
		// バリデーション
		if(!$this->thread->validate($inputs_tmp)) {
			return Redirect::to('/#thread-form')
				->withErrors($this->thread->errors())
				->withInput();
		}

		try {

			$thread_data = [
				'title' => $inputs['title_thread'],
				'body' => $inputs['body_thread'],
				'user_id' => $this->me->id,
			];
			$thread = $this->thread->create($thread_data);

			// ファイルが指定されている、存在する場合、ファイルを一時ディレクトリから移動
			if($inputs['tmpimg_path_thread']) {

				$tmpimg_path = Crypt::decrypt($inputs['tmpimg_path_thread']);
				if(file_exists($tmpimg_path)) {

					$file_url = '/assets/uploaded/' . $thread->id . '/';
					$file_dir = $_SERVER['DOCUMENT_ROOT'] . $file_url;
					if(!file_exists($file_dir)) {
						mkdir($file_dir);
						chmod($file_dir, 0777);
					}
					$file_url = $file_url . '1.' . $inputs['tmpimg_ext_thread'];
					$file_path = $file_dir . '1.' . $inputs['tmpimg_ext_thread'];

					rename($tmpimg_path, $file_path);

					$thread->uploaded_img = $file_url;
					$thread->save();

				}

			}

			return Redirect::to('/detail/' . $thread->id)
				->with('success', 'スレッドを作成しました');

		} catch(Exception $e) {

			return Redirect::to('/')
				->with('error', 'スレッドの作成に失敗しました');

		}

	}


	/**
	 * レス作成確認
	 */
	public function confirmRes() {

		$inputs = Input::only('body', 'tmpimg_url', 'tmpimg_path', 'tmpimg_ext', 'thread_id', 'from_list');

		$thread = $this->thread->find($inputs['thread_id']);
		if(!$thread) {
			return Redirect::to('/')
				->with('error', 'スレッドが存在しません');
		}

		$view_data['inputs'] = $inputs;
		return View::make('confirm_res', $view_data);

	}


	/**
	 * レス作成
	 */
	public function saveRes() {

		$inputs = Input::only('body', 'tmpimg_path', 'tmpimg_ext', 'thread_id');

		$thread = $this->thread->find($inputs['thread_id']);
		if(!$thread) {
			return Redirect::to('/')
				->with('error', 'スレッドが存在しません');
		}

		// 「戻る」押下時
		if(Input::get('_return')) {
			return Redirect::to('/detail/' . $thread->id . '/#res-form')
				->withInput();
		}

		// バリデーション
		if(!$this->res->validate($inputs)) {
			return Redirect::to('/detail/' . $thread->id . '/#res-form')
				->withErrors($this->res->errors())
				->withInput();
		}

		$res_last = $this->res->where('thread_id', $thread->id)
			->orderBy('res_no', 'desc')
			->first();
		if($res_last) {
			$res_no = $res_last->res_no + 1;
		} else {
			$res_no = 2;
		}

		try {

			$res_data = [
				'thread_id' => $thread->id,
				'res_no' => $res_no,
				'body' => $inputs['body'],
				'user_id' => $this->me->id,
			];
			$res = $this->res->create($res_data);

			// ファイルが指定されている、存在する場合、ファイルを一時ディレクトリから移動
			if($inputs['tmpimg_path']) {

				$tmpimg_path = Crypt::decrypt($inputs['tmpimg_path']);
				if(file_exists($tmpimg_path)) {

					$file_url = '/assets/uploaded/' . $thread->id . '/';
					$file_dir = $_SERVER['DOCUMENT_ROOT'] . $file_url;
					if(!file_exists($file_dir)) {
						mkdir($file_dir);
						chmod($file_dir, 0777);
					}
					$file_url = $file_url . $res->res_no .'.' . $inputs['tmpimg_ext'];
					$file_path = $file_dir . $res->res_no . '.' . $inputs['tmpimg_ext'];

					rename($tmpimg_path, $file_path);

					$res->uploaded_img = $file_url;
					$res->save();

				}

			}

			// スレッド一覧で表示順位を上げるための更新
			$thread->updated_at = $res->created_at;
			$thread->save();

			return Redirect::to('/detail/' . $thread->id)
				->with('success', '書き込みました <a href="#res_' . $res->res_no . '">>> ' . $res->res_no . '</a>');

		} catch(Exception $e) {

			return Redirect::to('/detail/' . $thread->id)
				->with('error', '書き込みに失敗しました');

		}

	}

}
