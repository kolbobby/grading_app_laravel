<div id="class_view_tabs">
	<ul>
		<li><a href="#assignments">Assignments</a></li>
		<li><a href="#students">Students</a></li>
	</ul>
	<div id="assignments">
		<div><a href="{{ URL::route('class-add-assignment', array('class_id' => $class_id)) }}">Add Assignment</a></div>
		@foreach($assignments as $assignment)
			<div>{{ $assignment }}</div>
		@endforeach
	</div>
	<div id="students">
		@foreach($students as $student)
			<div><a href="{{ URL::route('student-class-grade-page', array('student_id' => $student->id, 'class_id' => $class_id)) }}">{{ $student->name }}</a></div>
		@endforeach
	</div>
</div>