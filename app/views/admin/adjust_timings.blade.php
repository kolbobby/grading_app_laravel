<div class="c4 s4">
	<form class="wfull_form timings clearfix" action="{{ URL::route('admin-adjust-timings-post') }}" method="post">
		{{ Form::token() }}

		<div class="field">
			<div>Day Start (24 Hour Clock)</div>
			<div class="field side">
				<input type="text" name="day_start_hours" value="{{ $data['day_start_hours'] }}">
			</div>
			<div class="field side"> : </div>
			<div class="field side">
				<input type="text" name="day_start_minutes" value="{{ $data['day_start_minutes'] }}">
			</div>
			<div class="field side"> : </div>
			<div class="field side">
				<input type="text" name="day_start_seconds" value="{{ $data['day_start_seconds'] }}">
			</div>
			@if($errors->has('day_start_hours'))
				{{ $errors->first('day_start_hours') }}
			@endif
			@if($errors->has('day_start_minutes'))
				{{ $errors->first('day_start_minutes') }}
			@endif
			@if($errors->has('day_start_seconds'))
				{{ $errors->first('day_start_seconds') }}
			@endif
		</div>

		<div class="field">
			<div>Full Day Class Duration (in minutes)</div>
			<input type="text" name="fd_class_duration" value="{{ $data['fd_class_duration'] }}">
			@if($errors->has('fd_class_duration'))
				{{ $errors->first('fd_class_duration') }}
			@endif
		</div>


		<div class="field">
			<div>Full Day Time Between (in minutes)</div>
			<input type="text" name="fd_between_time" value="{{ $data['fd_between_time'] }}">
			@if($errors->has('fd_between_time'))
				{{ $errors->first('fd_between_time') }}
			@endif
		</div>

		<div class="field">
			<div>Half Day Class Duration (in minutes)</div>
			<input type="text" name="hd_class_duration" value="{{ $data['hd_class_duration'] }}">
			@if($errors->has('hd_class_duration'))
				{{ $errors->first('hd_class_duration') }}
			@endif
		</div>

		<div class="field">
			<div>Half Day Time Between (in minutes)</div>
			<input type="text" name="hd_between_time" value="{{ $data['hd_between_time'] }}">
			@if($errors->has('hd_between_time'))
				{{ $errors->first('hd_between_time') }}
			@endif
		</div>

		<input type="submit" value="Save Timings">
	</form>
</div>