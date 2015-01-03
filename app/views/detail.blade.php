@extends('layouts.master')


@section('javascript')
	<script>
		/*** サーバからのデータを取得 ***/
		var Svr_data = {};
	</script>
	<script type="text/javascript"
	        src="{{ url() . '/assets/app/js/require/require.min.js' }}"
	        data-main="pages/detail"></script>
@stop


@section('content')

<div>

	<div>{{{ $thread->title }}}</div>

	<div>
		1 {{{ $thread->user->name }}} {{{ $thread->created_at }}}<br>
		{{ nl2br($thread->body) }}
		@if($thread->uploaded_img)
			<img src="{{{ url() . $thread->uploaded_img }}}" />
		@endif
	</div>

	<br>

	@foreach($thread->ress as $res)
		<div id="res_{{{ $res->res_no }}}">
			{{{ $res->res_no }}} {{{ $res->user->name }}} {{{ $res->created_at }}}<br>
			{{ nl2br($res->body) }}
			@if($res->uploaded_img)
				<img src="{{{ url() . $res->uploaded_img }}}" />
			@endif
		</div>
	@endforeach

	{{-- レス作成フォーム --}}
	@if(!empty($me))

		<div id="res-form">

			@if($errors->all())
			<div class="alert alert-danger">
				@foreach($errors->all() as $error)
					{{ $error }}<br />
				@endforeach
			</div>
			@endif

			{{ Form::open(['url' => url() . '/res/confirm', 'files' => true, 'class' => 'form-inline']) }}
			<div class="panel-body pos-center">
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

				{{ Form::hidden('thread_id', $thread->id, []) }}
			</div>
			<div class="panel-body pos-center">
				{{ Form::submit('レス確認', ['class' => 'btn btn-primary']) }}
			</div>
			{{ Form::close() }}

		</div>

		@include('fbmodal')

	@endif


</div>


@stop
