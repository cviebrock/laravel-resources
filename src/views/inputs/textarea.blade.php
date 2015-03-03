@extends('resources::base.bootstrap')

@section('input.content')
	<label for="{{ $name }}">{{ $label }}</label>
  <textarea name="{{ $name }}" id="{{ $id }}" class="form-control" rows="3">{{ $value }}</textarea>
@overwrite
