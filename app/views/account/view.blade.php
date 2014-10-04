<div class="c12">
	Hello {{ Auth::user()->name }}.
</div>
<div class="c2">
	<ul>
		<li><a href="{{ URL::route('account-page') }}">Home</a></li>
		@if($accType == 'admin')
			<!-- Admin Sidebar Options -->
			<li><a href="{{ URL::route('admin-reserve-email') }}">Reserve Email</a></li>
			<li><a href="{{ URL::route('admin-add-student') }}">Add Student</a></li>
		@endif
	</ul>
</div>
<div class="c10">
	{{ $page }}
</div>