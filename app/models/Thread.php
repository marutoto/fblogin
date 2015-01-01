<?php

class Thread extends Base {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'threads';


	protected $fillable = array(
		'title',
		'body',
		'user_id',
	);


	/*** リレーション設定 ***/
	public function ress(){
		return $this->hasMany('Res');
	}

	public function user(){
		return $this->belongsTo('User');
	}

}
