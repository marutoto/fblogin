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

<div class="panel">
以下の内容でスレッドを作成します。
</div>

<div class="panel panel-default">

	{{ Form::open(['url' => url() . '/thread/save', 'files' => true, 'class' => 'form-inline']) }}
		<div class="panel-body">
			{{ Form::label('title_thread', 'タイトル', ['class' => 'control-label', 'for' => 'title_thread']) }}
			<div>{{{ $inputs['title_thread'] }}}</div>
			{{ Form::hidden('title_thread', $inputs['title_thread'], ['class' => 'form-control']) }}

			{{ Form::label('body_thread', '内容', ['class' => 'control-label', 'for' => 'body_thread']) }}
			<div>{{ nl2br($inputs['body_thread']) }}</div>
			{{ Form::hidden('body_thread', $inputs['body_thread'], ['class' => 'form-control', 'rows' => 3, 'cols' => 40]) }}

			@if($inputs['tmpimg_path_thread'] && $inputs['tmpimg_url_thread'])
				<img src="{{ url() . $inputs['tmpimg_url_thread'] }}" class="detail-img" />
			@endif
			{{ Form::hidden('tmpimg_url_thread', $inputs['tmpimg_url_thread'], []) }}
			{{ Form::hidden('tmpimg_path_thread', $inputs['tmpimg_path_thread'], []) }}
			{{ Form::hidden('tmpimg_ext_thread', $inputs['tmpimg_ext_thread'], []) }}

		</div>

		<div class="panel-footer pos-center">
			{{ Form::submit('戻る', ['class' => 'btn', 'name' => '_return']) }}
			{{ Form::submit('新規スレッド作成', ['class' => 'btn btn-primary']) }}
		</div>
		{{ Form::close() }}

</div>


@stop
