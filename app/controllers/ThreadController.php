<?php

class ThreadController extends BaseController {

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
	 * スレッド一覧
	 */
	public function index() {

		$view_data = [];

		$threads = Thread::with(['ress' => function($q) {
			$q->orderBy('res_no', 'desc');
		}])
			->orderBy('updated_at', 'desc')
			->orderBy('id', 'desc')
			->take(10)
			->get();

		$ress_all = [];
		foreach($threads as $thread) {
			$ress_all[$thread->id] = $thread->ress->take(10);
		}

		// 最新10レスを昇順に並べ直す
		$ress = [];
		foreach ($ress_all as $thread_id => $ress_tmp) {
			$ress[$thread_id] = [];
			foreach ($ress_tmp as $res) {
				$ress[$thread_id][] = $res;
			}
			$ress[$thread_id] = array_reverse($ress[$thread_id]);
		}

		$view_data['threads'] = $threads;
		$view_data['ress'] = $ress;
		return View::make('list', $view_data);

	}


	/**
	 * スレッド詳細
	 */
	public function detail($thread_id) {

		$thread = Thread::find($thread_id);
		if(!$thread) {
			Redirect::to('/')->with('error', 'スレッドがありません');;
		}

		$view_data['thread'] = $thread;
		return View::make('detail', $view_data);

	}

}
