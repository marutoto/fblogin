
define([

	// 使用するライブラリを指定
	'underscore',

], function (_) {

	// underscore.js _.template()のラップメソッド
	var template = function (template_selector, data) {
		var $template = jQuery(template_selector).html();
		var compiled = _.template($template);
		return compiled(data);
	};

	var us_wrap = {
		template: template,
	};


	// 外部へエクスポート
	return us_wrap;

});


