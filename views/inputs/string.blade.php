@extends('resources::base.bootstrap')

@section('input.content')
  <label for="{{ $fieldName }}">{{ $name }}</label>
  <input class="form-control" id="{{ $id }}" name="{{ $fieldName }}" type="text" value="{{ $value }}">
@overwrite
