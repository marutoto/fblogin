
define([

	// このページで使用するライブラリを指定
	'jquery',
	'underscore',
	'bootstrap',
	'modal',

], function (jQuery, _) {

	// モーダル設定
	$('.modal-link').modal({
        trigger: '.modal-link',          // id or class of link or button to trigger modal
        olay:'div.overlay',             // id or class of overlay
        modals:'div.modal',             // id or class of modal
        animationEffect: 'fadeIn',      // overlay effect | slideDown or fadeIn | default=fadeIn
        animationSpeed: 400,            // speed of overlay in milliseconds | default=400
        moveModalSpeed: 'slow',         // speed of modal movement when window is resized | slow or fast | default=false
        //background: 'a2d3cd',         // hexidecimal color code - DONT USE #
        background: '26816a',
        opacity: 0.2,                   // opacity of modal |  0 - 1 | default = 0.8
        openOnLoad: false,              // open modal on page load | true or false | default=false
        docClose: true,                 // click document to close | true or false | default=true
        closeByEscape: true,            // close modal by escape key | true or false | default=true
        moveOnScroll: true,             // move modal when window is scrolled | true or false | default=false
        resizeWindow: true,             // move modal when window is resized | true or false | default=false
        video: 'http://player.vimeo.com/video/2355334?color=eb5a3d',    // enter the url of the video
        videoClass:'video',             // class of video element(s)
        close:'.closeBtn'               // id or class of close button
    });


	// underscore.js _.template()のラップメソッド
	var template = function (template_selector, data) {
		var $template = jQuery(template_selector).html();
		var compiled = _.template($template);
		return compiled(data);
	};



	$('.fb-albums').click(function (e) {

		e.preventDefault();

		$.ajax({
			type: 'post',
			url: '/fbalbums',
			dataType: 'json',
			success: function (data) {

				var view_data = {
					albums: data.result.albums,
				};
				var html = template('#template_fb-albums-contents', view_data);
				$('#fb-modal-contents').empty().append(html);

			},
			error: function () {
				console.log('error');
			}
		});

	});

	$('body').on('click', '.fb-photos', function (e) {

		e.preventDefault();

		var $this = $(this);
		var album_id = $this.data('album_id');

		var post_data = {
			album_id: album_id,
		};
		$.ajax({
			type: 'post',
			url: '/fbphotos',
			data: post_data,
			dataType: 'json',
			success: function (data) {

				var view_data = {
					photos: data.result.photos,
				};
				var html = template('#template_fb-photos-contents', view_data);
				$('#fb-modal-contents').empty().append(html);

			},
			error: function () {
				console.log('error');
			}
		});

	});

	$('body').on('click', '.fb-upload', function (e) {

		e.preventDefault();

		var $this = $(this);
		var photo_orig_url = $this.data('photo_orig_url');
		var photo_name = $this.data('photo_name');

		var tmpimg_path = $('#hidden-tmpimg-path').val();

		var post_data = {
			photo_orig_url: photo_orig_url,
			photo_name: photo_name,
			tmpimg_path: tmpimg_path,
		};
		$.ajax({
			type: 'post',
			url: '/fbupload',
			data: post_data,
			dataType: 'json',
			success: function (data) {

				$('#hidden-tmpimg-path').val(data.result.tmpimg_info.path);

				var html = '<img src="' + data.result.tmpimg_info.url + '" width="70" height="70" />';
				$('#selected-img').empty().append(html);

			},
			error: function () {
				console.log('error');
			}
		});

	});


	return {
		jQuery: jQuery,
		template: template,
	};

});


