<header class="row">
	<div class="c2">
		<a class="brand regular" href="{{ URL::route('home') }}">Grading App</a>
	</div>
	<div class="c10">
		<ul class="navigation">
			@if(Auth::check())
				<li><a href="{{ URL::route('account-sign-out') }}">Sign Out</a></li>
			@else
				<li><a href="{{ URL::route('account-sign-in') }}">Sign In</a></li>
				<li><a href="{{ URL::route('account-create') }}">Sign Up</a></li>
			@endif
		</ul>
	</div>
</header>