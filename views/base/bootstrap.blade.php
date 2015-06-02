<div class="form-group{{ $errors->has($id) ? ' has-error' : ''}}">

	@yield('input.content')

	@if ($errors->has($id))
		<span class="help-block">{{$errors->first($id)}}</span>
	@endif
</div>
