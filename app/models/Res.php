<?php

class Res extends Base {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ress';


	protected $fillable = [
		'thread_id',
		'res_no',
		'body',
		'user_id',
	];


	/*** リレーション設定 ***/
	public function thread(){
		return $this->belongsTo('thread');
	}

	public function user(){
		return $this->belongsTo('User');
	}


	/*** バリデーション設定 ***/
	protected $attributes = [
		'body' => '内容',
	];

	protected $rules = [
		'body' => [
			'required',
		],
	];

}
