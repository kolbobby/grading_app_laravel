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

		<!-- jQuery UI CSS -->
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />

		<!-- Gridiculous CSS -->
		<link rel="stylesheet" href="{{ URL::asset('assets/css/gridiculous.css') }}" />

		<!-- Layout CSS -->
		<link rel="stylesheet" href="{{ URL::asset('assets/css/layout.css') }}" />
	</head>
	<body>
		<div class="grid wfull">
			{{ View::make('layouts.header') }}

			<div id="content" class="row">
				@if(Session::has('global'))
					<p>{{ Session::get('global') }}</p>
				@endif

				{{ $content }}
			</div>
		</div>

		<!-- jQuery -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<!-- jQuery UI -->
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>

		<!-- Admin Javascript -->
		<script type="text/javascript">
			(function($) {
				$('#parent_name').autocomplete({
					source: 'get_parents_json',
					minLength: 4,
					select: function(e, ui) {
						$('#parent_id').val(ui.item.id);
					}
				});
				$('#sc_name').autocomplete({
					source: 'get_school_counselors_json',
					minLength: 4,
					select: function(e, ui) {
						$('#sc_id').val(ui.item.id);
					}
				});
			}) (jQuery);
		</script>

		<!-- School Counselor Javascript -->
		<script type="text/javascript">
			(function($) {
				$('#search').autocomplete({
					source: 'get_school_classes_json',
					minLength: 1,
					select: function(e, ui) {
						$('#search_id').val(ui.item.id);
					}
				});
				$('#teacher_name').autocomplete({
					source: 'get_teachers_json',
					minLength: 4,
					select: function(e, ui) {
						$('#teacher_id').val(ui.item.id);
					}
				});
			}) (jQuery);
		</script>
	</body>
</html>