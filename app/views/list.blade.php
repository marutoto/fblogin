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
	<div class="panel panel-info">

		<div class="panel-heading">
			<a href="{{ url() . '/detail/' . $thread->id }}">{{{ $thread->title }}}</a>
		</div>

		<div class="panel-body">
			<div>
				1 {{{ $thread->user->name }}} {{{ str_replace('-', '/', $thread->created_at) }}}
			</div>
			<div class="res-body">
				{{ nl2br($thread->body) }}
				@if($thread->uploaded_img)
					<div>
						<img src="{{{ url() . $thread->uploaded_img }}}" class="list-img" />
					</div>
				@endif
			</div>
		</div>

		@foreach($ress[$thread->id] as $res)

			<div class="panel-body">
				<hr class="split-res">
				<div>
					{{{ $res->res_no }}} {{{ $res->user->name }}} {{{ str_replace('-', '/', $res->created_at) }}}
				</div>
				<div class="res-body">
					{{ nl2br($res->body) }}
					@if($res->uploaded_img)
						<div>
							<img src="{{{ url() . $res->uploaded_img }}}" class="list-img" />
						</div>
					@endif
				</div>
			</div>

		@endforeach

		{{ Form::open(['url' => url() . '/res/confirm', 'files' => true, 'class' => 'form-inline']) }}
			<div class="panel-body">
				<hr class="split-res">

				<div class="col-xs-8">
					{{ Form::label('body', '内容', ['class' => 'control-label', 'for' => 'body']) }}
					{{ Form::textarea('body', '', ['class' => 'form-control w100p', 'rows' => 3, 'cols' => 40]) }}
				</div>

				<div class="col-xs-4">
					<a class="modal-link fb-albums btn btn-default" href="#fb-modal">Facebook画像選択</a>
					{{ Form::hidden('tmpimg_url', '', ['class' => 'hidden-tmpimg-url']) }}
					{{ Form::hidden('tmpimg_path', '', ['class' => 'hidden-tmpimg-path']) }}
					{{ Form::hidden('tmpimg_ext', '', ['class' => 'hidden-tmpimg-ext']) }}
					<div class="selected-img">
						@if(Input::old('tmpimg_url'))
							<img src="{{ url() . Input::old('tmpimg_url') }}" class="detail-img" />
						@endif
					</div>
				</div>

				{{ Form::hidden('thread_id', $thread->id, []) }}
			</div>

			<div class="panel-body pos-center">
				{{ Form::hidden('from_list', 1, []) }}
				{{ Form::submit('書き込む', ['class' => 'btn btn-primary']) }}
			</div>
		{{ Form::close() }}

	</div>
	@endforeach

	{{-- スレッド作成フォーム --}}
	@if(!empty($me))

		<div id="thread-form" class="panel panel-default">

			@if($errors->all())
			<div class="alert alert-danger">
				@foreach($errors->all() as $error)
					{{ $error }}<br />
				@endforeach
			</div>
			@endif

			{{ Form::open(['url' => url() . '/thread/confirm', 'files' => true, 'class' => 'form-inline']) }}
				<div class="panel-body">
					<div class="col-xs-8">
						<div>
							<div>
								{{ Form::label('title_thread', 'タイトル', ['class' => 'control-label', 'for' => 'title_thread']) }}
							</div>
							<div>
								{{ Form::text('title_thread', '', ['class' => 'form-control w100p']) }}
							</div>
						</div>
						<div>
							<div>
								{{ Form::label('body_thread', '内容', ['class' => 'control-label', 'for' => 'body_thread']) }}
							</div>
							<div>
								{{ Form::textarea('body_thread', '', ['class' => 'form-control w100p', 'rows' => 3, 'cols' => 40]) }}
							</div>
						</div>
					</div>

					<div class="col-xs-4">
						<a class="modal-link fb-albums btn btn-default" href="#fb-modal">Facebook画像選択</a>
						{{ Form::hidden('tmpimg_url_thread', '', ['class' => 'hidden-tmpimg-url']) }}
						{{ Form::hidden('tmpimg_path_thread', '', ['class' => 'hidden-tmpimg-path']) }}
						{{ Form::hidden('tmpimg_ext_thread', '', ['class' => 'hidden-tmpimg-ext']) }}
						<div class="selected-img">
							@if(Input::old('tmpimg_url_thread'))
								<img src="{{ url() . Input::old('tmpimg_url_thread') }}" class="detail-img" />
							@endif
						</div>
					</div>

				</div>

				<div class="panel-footer pos-center">
					{{ Form::submit('新規スレッド作成', ['class' => 'btn btn-primary']) }}
				</div>
			{{ Form::close() }}

		</div>

		@include('fbmodal')

	@endif

@stop
