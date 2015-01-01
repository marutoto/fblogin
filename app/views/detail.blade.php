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
	</div>

	<br>

	@foreach($thread->ress as $res)
		<div id="res_{{{ $res->res_no }}}">
			{{{ $res->res_no }}} {{{ $res->user->name }}} {{{ $res->created_at }}}<br>
			{{ nl2br($res->body) }}
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

			{{ Form::open(array('url' => url() . '/res/confirm', 'files' => true, 'class' => 'form-inline')) }}
			<div class="panel-body pos-center">
				{{ Form::label('body', '内容', array('class' => 'control-label', 'for' => 'body')) }}
				{{ Form::textarea('body', '', array('class' => 'form-control', 'rows' => 3, 'cols' => 40)) }}

				{{ Form::hidden('thread_id', $thread->id) }}
				{{ Form::hidden('user_id', $me->id) }}
			</div>
			<div class="panel-body pos-center">
				{{ Form::submit('レス確認', array('class' => 'btn btn-primary')) }}
			</div>
			{{ Form::close() }}

		</div>
	@endif


</div>


@stop
