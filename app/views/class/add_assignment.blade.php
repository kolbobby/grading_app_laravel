<div class="c4 s4">
	<form class="wfull_form" action="" method="post">
		{{ Form::token() }}

		<div class="field">
			<input type="text" name="name" placeholder="Assignment Name">
			@if($errors->has('name'))
				{{ $errors->first('name') }}
			@endif
		</div>

		<div class="field">
			<select name="type">
				<option value="classwork">Classwork</option>
				<option value="homework">Homework</option>
				<option value="quiz">Quiz</option>
				<option value="test">Test</option>
			</select>
			@if($errors->has('type'))
				{{ $errors->first('type') }}
			@endif
		</div>

		<div class="field">
			<input type="text" name="points" placeholder="Assignment Points">
			@if($errors->has('points'))
				{{ $errors->first('points') }}
			@endif
		</div>

		<input type="submit" value="Add Assignment">
	</form>
</div>