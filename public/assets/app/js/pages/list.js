
// ライブラリをロード
require([

	// このページで使用するライブラリを指定
	'common',

// コールバック引数には、ライブラリごとの返り値が順に入る
], function (common) {

	// Facebook系イベントをセット
	common.fb.init();

});
