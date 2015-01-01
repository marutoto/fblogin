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

	{{ Form::open(array('url' => url() . '/thread/save', 'files' => true, 'class' => 'form-inline')) }}
		<div class="panel-body pos-center">
			{{ Form::label('title', 'タイトル', array('class' => 'control-label', 'for' => 'title')) }}
			<div>{{{ $inputs['title'] }}}</div>
			{{ Form::hidden('title', $inputs['title'], array('class' => 'form-control')) }}

			{{ Form::label('body', '内容', array('class' => 'control-label', 'for' => 'body')) }}
			<div>{{ nl2br($inputs['body']) }}</div>
			{{ Form::hidden('body', $inputs['body'], array('class' => 'form-control', 'rows' => 3, 'cols' => 40)) }}

			{{ Form::hidden('user_id', $me->id) }}
		</div>
		<div class="panel-body pos-center">
			{{ Form::submit('スレッド作成', array('class' => 'btn btn-primary')) }}
		</div>
		{{ Form::close() }}

</div>


@stop
