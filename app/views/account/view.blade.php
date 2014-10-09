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
			<li><a href="{{ URL::route('admin-adjust-timings') }}">Adjust Timing</a></li>
			<!--<li><a href="#">Add/Adjust Events</a></li>-->
		@elseif($accType == 'sc')
			<!-- School Counselor Options -->
			<li><a href="#">Add/Remove Classes</a></li>
		@endif
	</ul>
</div>
<div class="c10">
	@if($page)
		{{ $page }}
	@else
		@if($accType == 'admin')
			<div>{{ $data['current_period'] }}</div>
			@foreach($data['current_events'] as $event)
				<div>{{ $event }}</div>
			@endforeach
		@endif
		@foreach($data['students'] as $student)
			<div>{{ $student->name }}</div>
		@endforeach
	@endif
</div>