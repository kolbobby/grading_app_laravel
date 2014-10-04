<div class="c4 s4">
	<form class="wfull_form" action="{{ URL::route('admin-add-student-post') }}" method="post">
		{{ Form::token() }}

		<div class="field">
			<input type="text" name="student_name" id="student_name" placeholder="Student Name">
			@if($errors->has('student_name'))
				{{ $errors->first('student_name') }}
			@endif
		</div>

		<div class="field">
			<input type="hidden" name="parent_id" id="parent_id">
			<input type="text" name="parent_name" id="parent_name" placeholder="Parent Name">
			@if($errors->has('parent_name'))
				{{ $errors->first('parent_name') }}
			@endif
		</div>

		<div class="field">
			<input type="hidden" name="sc_id" id="sc_id">
			<input type="text" name="sc_name" id="sc_name" placeholder="School Counselor Name">
			@if($errors->has('sc_name'))
				{{ $errors->first('sc_name') }}
			@endif
		</div>

		<input type="submit" value="Add Student">
	</form>
</div>