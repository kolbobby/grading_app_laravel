<div class="c4 s4">
	<form class="wfull_form" action="{{ URL::route('sc-register-class-post') }}" method="post">
		{{ Form::token() }}

		<div class="field">
			<input type="hidden" name="search_id" id="search_id">
			<input type="text" name="search" id="search" placeholder="School Class Search ID">
			@if($errors->has('search'))
				{{ $errors->first('search') }}
			@endif
		</div>

		<div class="field">
			<input type="hidden" name="teacher_id" id="teacher_id">
			<input type="text" name="teacher_name" id="teacher_name" placeholder="Teacher Name">
			@if($errors->has('teacher_name'))
				{{ $errors->first('teacher_name') }}
			@endif
		</div>

		<div class="field">
			<input type="text" name="period" placeholder="Class Period">
			@if($errors->has('period'))
				{{ $errors->first('period') }}
			@endif
		</div>

		<input type="submit" value="Register Class">
	</form>
</div>