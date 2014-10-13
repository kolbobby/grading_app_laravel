<div id="student_view_tabs">
	<ul>
		<li><a href="#grades">Grades</a></li>
		@if($accType == 'sc')
			<li><a href="#schedule_classes">Schedule Classes</a></li>
		@endif
	</ul>
	<div id="grades">
		Grades
	</div>
	@if($accType == 'sc')
		<div id="schedule_classes">
			<form action="{{ URL::route('student-add-class', array('student_id' => $student_id)) }}" method="post">
				{{ Form::token() }}

				<div class="field">
					<input type="hidden" id="registered_class_id" name="registered_class_id">
					<input type="text" id="registered_class" name="registered_class" placeholder="Enter Class Search ID">
					@if($errors->has('registered_class'))
						{{ $errors->first('registered_class') }}
					@endif
				</div>

				<input type="submit" value="Add Class To Student Schedule">
			</form>
			<div class="classes">
				Student Schedule
			</div>
		</div>
	@endif
</div>