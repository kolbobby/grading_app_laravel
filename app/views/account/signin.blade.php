<div class="c12">
	<form action="{{ URL::route('account-sign-in-post') }}" method="post">
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

		<input type="submit" value="Sign In">
	</form>
</div>