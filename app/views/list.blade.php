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

@foreach($threads as $thread)
<div>

	<div>
		<a href="{{ url() . '/detail/' . $thread->id }}">{{{ $thread->title }}}</a>
	</div>

	<div>
		{{{ $thread->body }}}
	</div>

	<br>

	@foreach($ress[$thread->id] as $res)
		<div>
			{{{ $res['body'] }}}
		</div>
	@endforeach

</div>
<hr>
@endforeach

@stop
