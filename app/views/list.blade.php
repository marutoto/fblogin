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
			@if($thread->uploaded_img)
				<img src="{{{ url() . $thread->uploaded_img }}}" />
			@endif
		</div><br>

		<br>

		@foreach($ress[$thread->id] as $res)
			<div>
				{{{ $res->res_no }}} {{{ $res->user->name }}} {{{ $res->created_at }}}<br>
				{{ nl2br($res->body) }}<br>
				@if($res->uploaded_img)
					<img src="{{{ url() . $res->uploaded_img }}}" />
				@endif
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

				<div id="selected-img">
					@if(false)
						<img src="" />
					@endif
				</div>
				<a class="modal-link fb-albums btn btn-default" href="#fb-modal">Facebook画像選択</a>
				{{ Form::hidden('tmpimg_url', '', ['id' => 'hidden-tmpimg-url']) }}
				{{ Form::hidden('tmpimg_path', '', ['id' => 'hidden-tmpimg-path']) }}
				{{ Form::hidden('tmpimg_ext', '', ['id' => 'hidden-tmpimg-ext']) }}

				{{ Form::hidden('user_id', $me->id, []) }}
			</div>
			<div class="panel-body pos-center">
				{{ Form::submit('スレッド確認', ['class' => 'btn btn-primary']) }}
			</div>
			{{ Form::close() }}

		</div>

		@include('fbmodal')

	@endif

@stop
