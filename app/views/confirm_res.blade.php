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

	{{ Form::open(array('url' => url() . '/res/save', 'files' => true, 'class' => 'form-inline')) }}
		<div class="panel-body pos-center">
			{{ Form::label('body', '内容', array('class' => 'control-label', 'for' => 'body')) }}
			<div>{{ nl2br($inputs['body']) }}</div>
			{{ Form::hidden('body', $inputs['body'], array('class' => 'form-control', 'rows' => 3, 'cols' => 40)) }}

			@if($inputs['tmpimg_path'] && $inputs['tmpimg_url'])
				<img src="{{ url() . $inputs['tmpimg_url'] }}" />
			@endif
			{{ Form::hidden('tmpimg_path', $inputs['tmpimg_path'], []) }}
			{{ Form::hidden('tmpimg_ext', $inputs['tmpimg_ext'], []) }}

			{{ Form::hidden('thread_id', $inputs['thread_id']) }}
			{{ Form::hidden('user_id', $me->id) }}
		</div>
		<div class="panel-body pos-center">
			{{ Form::submit('レス作成', array('class' => 'btn btn-primary')) }}
		</div>
		{{ Form::close() }}

</div>


@stop
