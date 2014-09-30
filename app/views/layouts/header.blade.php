<header class="row">
	<div class="c2">
		<a class="brand regular" href="{{ URL::route('home') }}">Grading App</a>
	</div>
	<div class="c10">
		<ul class="navigation">
			@if(Auth::check())

			@else
				<li><a href="{{ URL::route('account-create') }}">Sign Up</a></li>
			@endif
		</ul>
	</div>
</header>