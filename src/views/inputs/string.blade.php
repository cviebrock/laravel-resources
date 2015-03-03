@extends('resources::base.bootstrap')

@section('input.content')
  <label for="{{ $name }}">{{ $label }}</label>
  <input class="form-control" id="{{ $id }}" name="{{ $name }}" type="text" value="{{ $value }}">
@overwrite
