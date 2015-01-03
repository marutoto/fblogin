<?php

class Base extends Eloquent {

	protected $softDelete = true;


	/*** バリデーション ***/
	protected $errors;

	// バリデーション実行メソッド
	public function validate(array $inputs) {

		$validator = Validator::make($inputs, $this->rules);
		$validator->setAttributeNames($this->attributes);

		if($validator->fails()) {
			$this->errors = $validator->messages();
			return false;
		}

		return true;

	}

	// バリデーションエラーを返す
	public function errors() {

		return $this->errors;

	}

}
