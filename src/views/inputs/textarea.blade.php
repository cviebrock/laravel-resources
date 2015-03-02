<div class="form-group{{ ($errors->has($id)) ? ' has-error': '' }}">
    <label class="control-label" for="{{ $name }}">{{ $label }}</label>
    <textarea id="{{ $id }}" class="form-control" name="{{ $name }}" rows="8">{{ $value }}</textarea>
    {{ $errors->first($id, '<span class="help-block">:message</span>') }}
</div>