<div class="c4 s4">
	<form class="wfull_form" action="{{ URL::route('sc-add-school-class-post') }}" method="post">
		{{ Form::token() }}

		<div class="field">
			<input type="text" name="search_id" placeholder="Class Search ID">
			@if($errors->has('search_id'))
				{{ $errors->first('search_id') }}
			@endif
		</div>

		<div class="field">
			<input type="text" name="name" placeholder="Class Name">
			@if($errors->has('name'))
				{{ $errors->first('name') }}
			@endif
		</div>

		<div class="field">
			<textarea name="description" placeholder="Description"></textarea>
			@if($errors->has('description'))
				{{ $errors->first('description') }}
			@endif
		</div>

		<input type="submit" value="Add Class">
	</form>
</div>