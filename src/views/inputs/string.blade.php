<div class="form-group{{ ($errors->has($id)) ? ' has-error': '' }}">
    <label class="control-label" for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $id }}" class="form-control" name="{{ $name }}" type="text" value="{{ $value }}"/>
    {{ $errors->first($id, '<span class="help-block">:message</span>') }}
</div>