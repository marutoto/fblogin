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
		{{{ $thread->body }}}
	</div>

	<br>

	@foreach($thread->ress as $res)
		<div>
			{{{ $res->body }}}
		</div>
	@endforeach

</div>


@stop
