<div class="c4 s4">
	<form class="wfull_form" action="{{ URL::route('account-sign-in-post') }}" method="post">
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
			<input type="checkbox" name="remember" id="remember">
			<label for="remember">Remember me</label>
		</div>

		<input type="submit" value="Sign In">

		<div class="field">
			<a href="{{ URL::route('account-forgot-password') }}">Forgot Password</a>
		</div>
	</form>
</div>