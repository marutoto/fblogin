
define([

	// 使用するライブラリを指定
	'jquery',
	'underscore_wrap',

], function (jQuery, us_wrap) {

	//
	var $this_imgbtn;

	// Facebook系処理のオブジェクト
	var fb = {

		// Facebook系処理の初期化
		init: function () {

			setEvent.getAlbums();
			setEvent.getPhotos();
			setEvent.uploadPhoto();
			setEvent.backAlbums();

		},

	};


	var setEvent = {

		// Facebookからアルバム一覧を取得する
		getAlbums: function () {

			$('body').on('click', '.fb-albums', function (e) {

				e.preventDefault();
				$('#fb-modal-contents').empty();

				$this_imgbtn = $(this);

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

							var html = us_wrap.template('#template_fb-no-permission', view_data);
							$('#fb-modal-contents').empty().append(html);

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
				$('#fb-modal-contents').empty();

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
				$('#fb-modal-contents').empty();

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

						$this_imgbtn.siblings('.hidden-tmpimg-url').val(data.result.tmpimg_info.url);
						$this_imgbtn.siblings('.hidden-tmpimg-path').val(data.result.tmpimg_info.path);
						$this_imgbtn.siblings('.hidden-tmpimg-ext').val(data.result.tmpimg_info.ext);

						view_data = {
							src: data.result.tmpimg_info.url,
						}
						var html = us_wrap.template('#template_fb-photos-imgarea', view_data);
						$this_imgbtn.siblings('.selected-img').empty().append(html);

						var view_data = {};
						var html = us_wrap.template('#template_fb-photos-complete', view_data);
						$('#fb-modal-contents').empty().append(html);

					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						console.log('error');
						console.log(errorThrown);
					}
				});

			});

		},


		// アルバム一覧へ戻る
		backAlbums: function () {

			$('body').on('click', '.fb-img-back', function (e) {

				e.preventDefault();
				$('#fb-modal-contents').empty();

				$this_imgbtn = $(this);

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

							var html = us_wrap.template('#template_fb-no-permission', view_data);
							$('#fb-modal-contents').empty().append(html);

						}

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


