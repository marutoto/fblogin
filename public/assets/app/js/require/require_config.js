
// require.js 設定ファイル
var require = {

	// ベースパスを定義（ドキュメントルートからのフルパス）
	baseUrl: DOC_ROOT + "assets/app/js/",

	// モジュールへのパスを設定（baseUrlからの相対パス）
	// ※ jsonで記述（シングルクォーテーションは使えない）
	paths: {

		/*** vendor ***/
		// jquery
		"jquery": "lib/vendor/jquery/jquery-1.11.0.min",

		// underscore
		"underscore": "lib/vendor/underscore/underscore-min",

		// twitter bootstrap
		"bootstrap": "../../bootstrap/3.1.0/js/bootstrap.min",

		// popeasy(modal)
		"modal": "lib/vendor/modal/popeasy/jquery.modal.min",

		/*** original ***/
		// common
		"common": "lib/original/common",

	},

	// モジュールの依存関係を定義（読み込む順を指定することができる）
	shim: {

		/*** vendor ***/
		"underscore": {
			exports: '_',
		},
		"bootstrap": {
			deps: ['jquery'],
		},
		"modal": {
			deps: ['jquery'],
		},

		/*** original ***/
		// common
		"common": {
			exports: 'common',
			deps: ['jquery', 'bootstrap', 'underscore', 'modal'],
		},
	}



};
