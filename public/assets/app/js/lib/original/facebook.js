
define([

	// 使用するライブラリを指定
	'jquery',
	'underscore_wrap',

], function (jQuery, us_wrap) {

	// Facebook系処理のオブジェクト
	var fb = {

		// Facebook系処理の初期化
		init: function () {

			setEvent.getAlbums();
			setEvent.getPhotos();
			setEvent.uploadPhoto();

		},

	};


	var setEvent = {

		// Facebookからアルバム一覧を取得する
		getAlbums: function () {

			$('.fb-albums').click(function (e) {

				e.preventDefault();

				$.ajax({
					type: 'post',
					url: '/fb/albums',
					dataType: 'json',
					success: function (data) {

						if(data.result.fb_user_photos_permission) {

							if(data.result.albums.length > 0) {
								var view_data = {
									albums: data.result.albums,
								};
								var html = us_wrap.template('#template_fb-albums-contents', view_data);
								$('#fb-modal-contents').empty().append(html);
							} else {
								$('#fb-modal-contents').empty().append('Facebookアルバムがありません');
							}

						} else {

							$('#fb-modal-contents').empty().append('<a href="/fb/permitUserphotos">permit</a>');

						}

					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						console.log('error');
						console.log(errorThrown);
					}
				});

			});

		},

		// Facebookから写真一覧を取得する
		getPhotos: function () {

			$('body').on('click', '.fb-photos', function (e) {

				e.preventDefault();

				var $this = $(this);
				var album_id = $this.data('album_id');

				var post_data = {
					album_id: album_id,
				};
				$.ajax({
					type: 'post',
					url: '/fb/photos',
					data: post_data,
					dataType: 'json',
					success: function (data) {

						var view_data = {
							photos: data.result.photos,
						};
						var html = us_wrap.template('#template_fb-photos-contents', view_data);
						$('#fb-modal-contents').empty().append(html);

					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						console.log('error');
						console.log(errorThrown);
					}
				});

			});

		},

		// Facebookから取得した写真を一時ディレクトリへアップロードする
		uploadPhoto: function () {

			$('body').on('click', '.fb-upload', function (e) {

				e.preventDefault();

				var $this = $(this);
				var photo_orig_url = $this.data('photo_orig_url');
				var photo_name = $this.data('photo_name');
				var photo_ext = $this.data('photo_ext');

				var tmpimg_path = $('#hidden-tmpimg-path').val();

				var post_data = {
					photo_orig_url: photo_orig_url,
					photo_name: photo_name,
					photo_ext: photo_ext,
					tmpimg_path: tmpimg_path,
				};
				$.ajax({
					type: 'post',
					url: '/fb/uploadPhoto',
					data: post_data,
					dataType: 'json',
					success: function (data) {

						$('#hidden-tmpimg-url').val(data.result.tmpimg_info.url);
						$('#hidden-tmpimg-path').val(data.result.tmpimg_info.path);
						$('#hidden-tmpimg-ext').val(data.result.tmpimg_info.ext);

						var html = '<img src="' + data.result.tmpimg_info.url + '" width="70" height="70" />';
						$('#selected-img').empty().append(html);

					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						console.log('error');
						console.log(errorThrown);
					}
				});

			});

		},

	};


	// 外部へエクスポート
	return fb;

});


