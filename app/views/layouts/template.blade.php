<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
	<head>
		<title>{{ $title }}</title>
		<meta charset="utf-8">
		<meta name="author" content="">
		<meta name="description" content="">

		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicon -->
		<link rel="shortcut icon" href="">
		<link rel="apple-touch-icon" href="">
		<link rel="apple-touch-icon" sizes="72x72" href="">
		<link rel="apple-touch-icon" sizes="114x114" href="">

		<!-- Google Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Oswald:300,400,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>

		<!-- Gridiculous CSS -->
		<link rel="stylesheet" href="{{ URL::asset('assets/css/gridiculous.css') }}" />

		<!-- Layout CSS -->
		<link rel="stylesheet" href="{{ URL::asset('assets/css/layout.css') }}" />
	</head>
	<body>
		<div class="grid wfull">
			<div class="row">
				{{ View::make('layouts.header') }}
			</div>

			<div id="content" class="row">
				{{ $content }}
			</div>
		</div>
	</body>
</html>