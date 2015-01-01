<?php

class Res extends Base {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ress';


	/*** リレーション設定 ***/
	public function thread(){
		return $this->belongsTo('thread');
	}

	public function user(){
		return $this->belongsTo('User');
	}

}
