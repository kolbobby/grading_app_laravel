<div class="c4 s4">
	<form class="wfull_form" action="{{ URL::route('account-forgot-password-post') }}" method="post">
		{{ Form::token() }}

		<div class="field">
			<input type="text" name="email" placeholder="Email"{{ (Input::old('email')) ? ' value="' . e(Input::old('email')) . '"' : '' }}>
			@if($errors->has('email'))
				{{ $errors->first('email') }}
			@endif
		</div>

		<input type="submit" value="Recover Password">
	</form>
</div>