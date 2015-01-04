<div id="fb-modal-area">
	<div class="overlay"></div>
	<div id="fb-modal" class="modal panel panel-default">

		<div id="fb-modal-contents"></div>

	</div>
</div>

<script type="text/template" id="template_fb-albums-contents">
	アルバムを選択してください。
	<ul class="list-group">
	<% for(var i = 0, length = albums.length; i < length; i++) { %>
		<li class="fb-photos list-group-item" data-album_id="<%=albums[i].id %>">
			<a href="#"><%=albums[i].name %></a>
		</li>
	<% } %>
	</ul>
</script>

<script type="text/template" id="template_fb-photos-contents">
	写真を選択してください。
	<ul class="list-group">
	<% for(var i = 0, length = photos.length; i < length; i++) { %>
		<li class="fb-upload list-group-item" data-photo_orig_url="<%=photos[i].orig_url %>" data-photo_name="<%=photos[i].name %>" data-photo_ext="<%=photos[i].ext %>">
			<a href="#"><img src="<%=photos[i].orig_url %>" class="list-img" /></a>
		</li>
	<% } %>
	</ul>
	<button class="fb-img-back btn">戻る</button>
</script>

<script type="text/template" id="template_fb-photos-complete">
	<div class="alert alert-success">
		写真の選択が完了しました。ポップアップを閉じてください。
	</div>
</script>

<script type="text/template" id="template_fb-no-permission">
	<div class="alert alert-danger">
		Facebookの写真利用が許可されていません。<br />
		<a href="/fb/permitUserphotos">こちら</a>をクリックして、利用許可を完了してください。<br />
		<br />
		※許可後はスレッド一覧画面へ遷移します
	</div>
</script>

<script type="text/template" id="template_fb-photos-imgarea">
	<div class="panel-body">
		<img src="<%=src %>" class="detail-img" />
	</div>
</script>
