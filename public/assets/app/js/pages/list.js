
// ライブラリをロード
require([

	// このページで使用するライブラリを指定
	// require_config.jsのpathsを利用している
	'jquery',
	'bootstrap',

// コールバック引数には、ライブラリごとの返り値が順に入る
], function (jQuery) {

	/*
	// グローバルに関数を定義する場合
	window.minus = function (a, b) {
		alert('global');
		alert(a-b);
	};

	// グローバルにライブラリの関数を定義する場合
	window.minus = mylib1.minus;
	*/

});
