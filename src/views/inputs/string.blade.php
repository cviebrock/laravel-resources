<div class="form-group{{ $errors->has($id) ? ' has-error' : ''}}">
    <label for="{{ $name }}">{{ $label }}</label>
    <input class="form-control" id="{{ $id }}" name="{{ $name }}" type="text" value="{{ $value }}">
		@if ($errors->has($id))
			<span class="help-block">{{$errors->first($id)}}</span>
		@endif
</div>
