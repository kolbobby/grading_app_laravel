<div class="c12">
	<form action="{{ URL::route('account-change-password-post') }}" method="post">
		{{ Form::token() }}

		<div class="field">
			<input type="password" name="old_password" placeholder="Old Password">
			@if($errors->has('old_password'))
				{{ $errors->first('old_password') }}
			@endif
		</div>

		<div class="field">
			<input type="password" name="new_password" placeholder="New Password">
			@if($errors->has('new_password'))
				{{ $errors->first('new_password') }}
			@endif
		</div>

		<div class="field">
			<input type="password" name="confirm_new_password" placeholder="Confirm New Password">
			@if($errors->has('confirm_new_password'))
				{{ $errors->first('confirm_new_password') }}
			@endif
		</div>

		<input type="submit" value="Change Password">
	</form>
</div>