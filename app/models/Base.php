<?php

class Base extends Eloquent {

	protected $softDelete = true;

	// バリデーション実行メソッド
	public function validate(array $params) {

		$validator = Validator::make($params, $this->rules);
		$validator->setAttributeNames($this->attributes);

		if(!$validator->passes()) {
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
