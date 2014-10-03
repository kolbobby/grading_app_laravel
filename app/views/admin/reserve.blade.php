<div class="c4 s4">
	<form class="wfull_form" action="{{ URL::route('admin-reserve-email-post') }}" method="post">
		{{ Form::token() }}

		<div class="field">
			<input type="text" name="email" placeholder="Email"{{ (Input::old('email')) ? ' value="' . e(Input::old('email')) . '"' : '' }}>
			@if($errors->has('email'))
				{{ $errors->first('email') }}
			@endif
		</div>

		<div class="field">
			<select name="type">
				<option value="admin">Admin</option>
				<option value="parent">Parent</option>
				<option value="teacher">Teacher</option>
				<option value="school_counselor">School Counselor</option>
			</select>
		</div>

		<input type="submit" value="Reserve Email">
	</form>
</div>