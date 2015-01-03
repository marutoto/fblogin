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
