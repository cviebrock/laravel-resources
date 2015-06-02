@extends('resources::base.bootstrap')

@section('input.content')
	<label for="{{ $fieldName }}">{{ $name }}</label>
  <textarea name="{{ $fieldName }}" id="{{ $id }}" class="form-control" rows="3">{{ $value }}</textarea>
@overwrite
