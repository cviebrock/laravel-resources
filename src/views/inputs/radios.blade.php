@extends('resources::base.bootstrap')

@section('input.content')
	<label>{{ $label }}</label>
	@foreach( $choices as $_key => $_choice )
		<div class="radio">
			<label>
				<input type="radio" value="{{ $_key }}" id="{{ $id }}--{{ $_key }}" name="{{ $name }}"
					@if($_key == $value)
						checked="checked"
					@endif
				>
				{{ $_choice }}
			</label>
		</div>
	@endforeach
@overwrite
