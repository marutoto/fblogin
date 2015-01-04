<div id="fb-modal-area">
	<div class="overlay"></div>
	<div id="fb-modal" class="modal panel panel-default">

		<div id="fb-modal-contents"></div>

	</div>
</div>

<script type="text/template" id="template_fb-albums-contents">
	<% for(var i = 0, length = albums.length; i < length; i++) { %>
		<div class="fb-photos" data-album_id="<%=albums[i].id %>">
			<a href="#"><%=albums[i].name %></a>
		</div>
	<% } %>
</script>

<script type="text/template" id="template_fb-photos-contents">
	<% for(var i = 0, length = photos.length; i < length; i++) { %>
		<div class="fb-upload" data-photo_orig_url="<%=photos[i].orig_url %>" data-photo_name="<%=photos[i].name %>" data-photo_ext="<%=photos[i].ext %>">
			<a href="#"><img src="<%=photos[i].orig_url %>" class="list-img" /></a>
		</div>
	<% } %>
</script>
