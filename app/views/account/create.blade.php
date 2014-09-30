<div class="c12">
	<form action="{{ URL::route('account-create-post') }}" method="post">
		{{ Form::token() }}

		<div class="field">
			<input type="text" name="email" placeholder="Email"{{ (Input::old('email')) ? ' value="' . e(Input::old('email')) . '"' : '' }}>
			@if($errors->has('email'))
				{{ $errors->first('email') }}
			@endif
		</div>

		<div class="field">
			<input type="password" name="password" placeholder="Password">
			@if($errors->has('password'))
				{{ $errors->first('password') }}
			@endif
		</div>

		<div class="field">
			<input type="password" name="confirm_password" placeholder="Confirm Password">
			@if($errors->has('confirm_password'))
				{{ $errors->first('confirm_password') }}
			@endif
		</div>

		<input type="submit" value="Register">
	</form>
</div>