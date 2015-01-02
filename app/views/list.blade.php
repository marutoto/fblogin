@extends('layouts.master')


@section('javascript')
	<script>
		/*** サーバからのデータを取得 ***/
		var Svr_data = {};
	</script>
	<script type="text/javascript"
	        src="{{ url() . '/assets/app/js/require/require.min.js' }}"
	        data-main="pages/list"></script>
@stop


@section('content')

	{{-- スレッド一覧 --}}
	@foreach($threads as $thread)
	<div>

		<div>
			<a href="{{ url() . '/detail/' . $thread->id }}">{{{ $thread->title }}}</a>
		</div>

		<div>
			1 {{{ $thread->user->name }}} {{{ $thread->created_at }}}<br>
			{{ nl2br($thread->body) }}
		</div><br>

		<br>

		@foreach($ress[$thread->id] as $res)
			<div>
				{{{ $res->res_no }}} {{{ $res->user->name }}} {{{ $res->created_at }}}<br>
				{{ nl2br($res->body) }}
			</div>
		@endforeach

	</div>
	<hr>
	@endforeach

	{{-- スレッド作成フォーム --}}
	@if(!empty($me))
		<div id="thread-form">

			@if($errors->all())
			<div class="alert alert-danger">
				@foreach($errors->all() as $error)
					{{ $error }}<br />
				@endforeach
			</div>
			@endif

			{{ Form::open(['url' => url() . '/thread/confirm', 'files' => true, 'class' => 'form-inline']) }}
			<div class="panel-body pos-center">
				{{ Form::label('title', 'タイトル', ['class' => 'control-label', 'for' => 'title']) }}
				{{ Form::text('title', '', ['class' => 'form-control']) }}
				{{ Form::label('body', '内容', ['class' => 'control-label', 'for' => 'body']) }}
				{{ Form::textarea('body', '', ['class' => 'form-control', 'rows' => 3, 'cols' => 40]) }}

				<div id="selected-img"></div>
				<a class="modal-link fb-albums btn btn-default" href="#fb-modal">Facebook画像選択</a>
				{{ Form::hidden('tmpimg_path', '', ['id' => 'hidden-tmpimg-path']) }}

				{{ Form::hidden('user_id', $me->id, []) }}
			</div>
			<div class="panel-body pos-center">
				{{ Form::submit('スレッド確認', ['class' => 'btn btn-primary']) }}
			</div>
			{{ Form::close() }}

		</div>

		<!--TODO:view切り出す-->
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
				<div class="fb-upload" data-photo_orig_url="<%=photos[i].orig_url %>" data-photo_name="<%=photos[i].name %>">
					<a href="#"><img src="<%=photos[i].orig_url %>" width="70" height="70" /></a>
				</div>
			<% } %>
		</script>
	@endif

@stop
