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

		$inputs = Input::only('title', 'body', 'tmpimg_url', 'tmpimg_path', 'tmpimg_ext');

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

	}


	/**
	 * スレッド作成
	 */
	public function saveThread() {

		$inputs = Input::only('title', 'body', 'tmpimg_path', 'tmpimg_ext');

		try {

			$thread_data = [
				'title' => $inputs['title'],
				'body' => $inputs['body'],
				'user_id' => $this->me->id,
			];
			$thread = Thread::create($thread_data);

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
					$file_url = $file_url . '1.' . $inputs['tmpimg_ext'];
					$file_path = $file_dir . '1.' . $inputs['tmpimg_ext'];

					rename($tmpimg_path, $file_path);

					$thread->uploaded_img = $file_url;
					$thread->save();

				}

			}

			return Redirect::to('/detail/' . $thread->id)->with('success', 'スレッドを作成しました');

		} catch(Exception $e) {

			return Redirect::to('/')->with('error', 'スレッドの作成に失敗しました');

		}

	}


	/**
	 * レス作成確認
	 */
	public function confirmRes() {

		$inputs = Input::only('body', 'tmpimg_url', 'tmpimg_path', 'tmpimg_ext', 'thread_id');

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

	}


	/**
	 * レス作成
	 */
	public function saveRes() {

		$inputs = Input::only('body', 'tmpimg_path', 'tmpimg_ext', 'thread_id');

		$thread = Thread::find($inputs['thread_id']);
		if(!$thread) {
			return Redirect::to('/')->with('error', 'スレッドが存在しません');
		}

		$res_last = Res::where('thread_id', $thread->id)
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
			$res = Res::create($res_data);

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

			return Redirect::to('/detail/' . $thread->id)->with('success', 'レスしました <a href="#res_' . $res->res_no . '">>> ' . $res->res_no . '</a>');

		} catch(Exception $e) {

			return Redirect::to('/detail/' . $thread->id)->with('error', 'レスに失敗しました');

		}

	}

}
