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

			{{ Form::open(array('url' => url() . '/thread/confirm', 'files' => true, 'class' => 'form-inline')) }}
			<div class="panel-body pos-center">
				{{ Form::label('title', 'タイトル', array('class' => 'control-label', 'for' => 'title')) }}
				{{ Form::text('title', '', array('class' => 'form-control')) }}
				{{ Form::label('body', '内容', array('class' => 'control-label', 'for' => 'body')) }}
				{{ Form::textarea('body', '', array('class' => 'form-control', 'rows' => 3, 'cols' => 40)) }}
				{{-- Form::file('csv', array('class' => 'form-control right-margin')) --}}

				{{ Form::hidden('user_id', $me->id) }}
			</div>
			<div class="panel-body pos-center">
				{{ Form::submit('スレッド確認', array('class' => 'btn btn-primary')) }}
			</div>
			{{ Form::close() }}

		</div>
	@endif

@stop
