
// require.js 設定ファイル
var require = {

	// ベースパスを定義（ドキュメントルートからのフルパス）
	baseUrl: DOC_ROOT + "assets/app/js/",

	// モジュールへのパスを設定（baseUrlからの相対パス）
	// ※ jsonで記述（シングルクォーテーションは使えない）
	paths: {

		/*** 共通ライブラリ ***/
		// twitter bootstrap
		"bootstrap": "../../bootstrap/3.1.0/js/bootstrap.min",

		// jquery
		"jquery": "lib/vendor/jquery/jquery-1.11.0.min",

		// original
		//"register": "lib/my/register",

	},

	// モジュールの依存関係を定義（読み込む順を指定することができる）
	shim: {
		/*-----------------↓サンプル（後で書き直す）↓------------------*/
		// jquery-uiの依存関係を定義
		// 簡易定義
		//"jquery.ui.core": ['jquery'],
		//"jquery.ui.widget": ['jquery.ui.core'],
		//"jquery.ui.mouse": ['jquery.ui.widget'],
		//"jquery.ui.draggable": ['jquery.ui.mouse'],
		//"jquery.ui.dialog": ['jquery.ui.widget'],
		/*-----------------↑サンプル（後で書き直す）↑------------------*/

		"bootstrap": ['jquery'],

		// original
		//"register": ['jquery'],
	}



};
