<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Base implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];


	protected $fillable = [
		'fbid',
		'first_name',
		'last_name',
		'name',
		'gender',
		'photo',
	];


	/*** リレーション設定 ***/
	public function threads(){
		return $this->hasMany('Thread');
	}

	public function responses(){
		return $this->hasMany('Response');
	}

}
