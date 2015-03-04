@extends('resources::base.bootstrap')

@section('input.content')
	<label>{{ $name }}</label>
	{{-- hidden element to force the input to be there, even if all the checkboxes are empty --}}
	<input type="hidden" name="{{ $fieldName }}">
	@foreach( $choices as $_key => $_choice )
		<div class="checkbox">
			<label>
				<input type="checkbox" value="{{ $_key }}" id="{{ $id }}" name="{{ $fieldName }}[]"
					@if(is_array($value) && in_array($_key, $value))
						checked="checked"
					@endif
				>
				{{ $_choice }}
			</label>
		</div>
	@endforeach
@overwrite
