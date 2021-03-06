@extends('layouts.master')


@section('javascript')
	<script>
		/*** サーバからのデータを取得 ***/
		var Svr_data = {};
	</script>
	<script type="text/javascript"
	        src="{{ url() . '/assets/app/js/require/require.min.js' }}"
	        data-main="pages/confirm_res"></script>
@stop


@section('content')

<div class="panel">
以下の内容で書き込みます。
</div>

<div class="panel panel-default">

	{{ Form::open(array('url' => url() . '/res/save', 'files' => true, 'class' => 'form-inline')) }}
		<div class="panel-body disp-string">
			{{ Form::label('body', '内容', array('class' => 'control-label', 'for' => 'body')) }}
			<div>{{ nl2br(htmlspecialchars($inputs['body'], ENT_QUOTES, 'utf-8')) }}</div>
			{{ Form::hidden('body', $inputs['body'], array('class' => 'form-control', 'rows' => 3, 'cols' => 40)) }}

			@if($inputs['tmpimg_path'] && $inputs['tmpimg_url'])
				<img src="{{ url() . $inputs['tmpimg_url'] }}" class="detail-img" />
			@endif
			{{ Form::hidden('tmpimg_url', $inputs['tmpimg_url'], []) }}
			{{ Form::hidden('tmpimg_path', $inputs['tmpimg_path'], []) }}
			{{ Form::hidden('tmpimg_ext', $inputs['tmpimg_ext'], []) }}

			{{ Form::hidden('thread_id', $inputs['thread_id']) }}
		</div>

		<div class="panel-footer pos-center">
			{{ Form::submit('戻る', ['class' => 'btn', 'name' => '_return']) }}<span class="comment">※スレッド詳細へ戻ります</span>&nbsp;&nbsp;&nbsp;&nbsp;
			{{ Form::submit('書き込む', array('class' => 'btn btn-primary')) }}
		</div>
	{{ Form::close() }}

</div>


@stop
