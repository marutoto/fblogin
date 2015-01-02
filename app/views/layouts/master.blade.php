<!DOCTYPE html>
<html lang="ja-JP">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="{{ url().'/assets/bootstrap/3.1.0/css/bootstrap.min.css' }}">
		<link rel="stylesheet" href="{{ url().'/assets/app/css/common/override_bootstrap.css' }}">
		<link rel="stylesheet" href="{{ url().'/assets/app/css/common/vendor/modal/popeasy/main.css' }}">
		<link rel="stylesheet" href="{{ url().'/assets/app/css/common/style.css' }}">

		<script>
			var DOC_ROOT = '{{ $doc_root }}';
		</script>
		<script type="text/javascript" src="{{ url() . '/assets/app/js/require/require_config.js' }}"></script>
		@yield('javascript')

	</head>
	<body role="document" style="padding-top: 50px;">
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					@if(!empty($me))
						<img src="{{ $me['photo']}}" width="50" height="50" >
						{{{ $me['name'] }}}さん <a href="logout">ログアウト</a>
					@else
						<a href="login/fb">Facebookログイン</a>
					@endif
					<a class="navbar-brand" href="{{ url() }}">fblogin.marutoto.com</a>
				</div>
			</div>
		</div>

		{{-- メッセージ領域 --}}
		@if(Session::has('success'))
		<div class="alert alert-success">
			{{ Session::get('success')}}
		</div>
		@endif

		@if(Session::has('error'))
		<div class="alert alert-danger">
			{{ Session::get('error')}}
		</div>
		@endif

		<div class="container theme-showcase" role="main">

			@yield('content')

		</div>

		{{--@yield('footer')--}}

	</body>
</html>
