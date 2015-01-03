@extends('layouts.master')


@section('javascript')
	<script>
		/*** サーバからのデータを取得 ***/
		var Svr_data = {};
	</script>
	<script type="text/javascript"
	        src="{{ url() . '/assets/app/js/require/require.min.js' }}"
	        data-main="pages/confirm_thread"></script>
@stop


@section('content')

<div>

	{{ Form::open(['url' => url() . '/thread/save', 'files' => true, 'class' => 'form-inline']) }}
		<div class="panel-body pos-center">
			{{ Form::label('title', 'タイトル', ['class' => 'control-label', 'for' => 'title']) }}
			<div>{{{ $inputs['title'] }}}</div>
			{{ Form::hidden('title', $inputs['title'], ['class' => 'form-control']) }}

			{{ Form::label('body', '内容', ['class' => 'control-label', 'for' => 'body']) }}
			<div>{{ nl2br($inputs['body']) }}</div>
			{{ Form::hidden('body', $inputs['body'], ['class' => 'form-control', 'rows' => 3, 'cols' => 40]) }}

			@if($inputs['tmpimg_path'] && $inputs['tmpimg_url'])
				<img src="{{ url() . $inputs['tmpimg_url'] }}" />
			@endif
			{{ Form::hidden('tmpimg_url', $inputs['tmpimg_url'], []) }}
			{{ Form::hidden('tmpimg_path', $inputs['tmpimg_path'], []) }}
			{{ Form::hidden('tmpimg_ext', $inputs['tmpimg_ext'], []) }}

		</div>
		<div class="panel-body pos-center">
			{{ Form::submit('戻る', ['class' => 'btn', 'name' => '_return']) }}
			{{ Form::submit('スレッド作成', ['class' => 'btn btn-primary']) }}
		</div>
		{{ Form::close() }}

</div>


@stop
