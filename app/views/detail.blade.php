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

<div class="panel panel-info">

	<div class="panel-heading">
		{{{ $thread->title }}}
	</div>

	<div class="panel-body">
		<div>
			1 {{{ $thread->user->name }}} {{{ str_replace('-', '/', $thread->created_at) }}}
		</div>
		<div class="res-body">
			{{ nl2br($thread->body) }}
			@if($thread->uploaded_img)
				<div>
					<img src="{{{ url() . $thread->uploaded_img }}}" class="detail-img" />
				</div>
			@endif
		</div>
	</div>

	<br>

	@foreach($thread->ress as $res)

		<div class="panel-body" id="res_{{{ $res->res_no }}}">
			<hr class="split-res">
			<div>
				{{{ $res->res_no }}} {{{ $res->user->name }}} {{{ str_replace('-', '/', $res->created_at) }}}<br>
			</div>
			<div class="res-body">
				{{ nl2br($res->body) }}
				@if($res->uploaded_img)
					<div>
						<img src="{{{ url() . $res->uploaded_img }}}" class="detail-img" />
					</div>
				@endif
			</div>
		</div>

	@endforeach

</div>


{{-- レス作成フォーム --}}
@if(!empty($me))

	<div id="res-form" class="panel panel-default">

		@if($errors->all())
		<div class="alert alert-danger">
			@foreach($errors->all() as $error)
				{{ $error }}<br />
			@endforeach
		</div>
		@endif

		{{ Form::open(['url' => url() . '/res/confirm', 'files' => true, 'class' => 'form-inline']) }}
			<div class="panel-body">
				<div class="col-xs-8">
					{{ Form::label('body', '内容', ['class' => 'control-label', 'for' => 'body']) }}
					{{ Form::textarea('body', '', ['class' => 'form-control w100p', 'rows' => 3, 'cols' => 40]) }}
				</div>

				<div class="col-xs-4">
					<a class="modal-link fb-albums btn btn-default" href="#fb-modal">Facebook画像選択</a>
					{{ Form::hidden('tmpimg_url', '', ['id' => 'hidden-tmpimg-url']) }}
					{{ Form::hidden('tmpimg_path', '', ['id' => 'hidden-tmpimg-path']) }}
					{{ Form::hidden('tmpimg_ext', '', ['id' => 'hidden-tmpimg-ext']) }}
					<div id="selected-img">
						@if(Input::old('tmpimg_url'))
							<img src="{{ url() . Input::old('tmpimg_url') }}" class="detail-img" />
						@endif
					</div>
				</div>

				{{ Form::hidden('thread_id', $thread->id, []) }}
			</div>

			<div class="panel-footer pos-center">
				{{ Form::submit('レス確認', ['class' => 'btn btn-primary']) }}
			</div>
		{{ Form::close() }}

	</div>

	@include('fbmodal')

@endif

@stop
