{{--
    Renders a basic form for managing the given resources
--}}
<form role="form" action="{{ $action }}" method="{{ $method }}"@foreach($attributes as $attr => $val) {{ $attr . '=' . $val  }}@endforeach>
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    {{--Render the resource inputs--}}
    @foreach($resources as $resource)
        {{ $resource->renderInput() }}
    @endforeach

    <button type="submit" class="btn btn-primary">Submit</button>
</form>