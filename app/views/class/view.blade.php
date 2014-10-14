@foreach($students as $student)
	<div><a href="{{ URL::route('student-class-grade-page', array('student_id' => $student->id, 'class_id' => $class_id)) }}">{{ $student->name }}</a></div>
@endforeach