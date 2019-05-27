<div style="max-height: 80vh; overflow: scroll">

    @foreach($fields as $name => $field)

        <h4>{{ __('reflection.fields.' . $type .'.'.$name) }}</h4>
        <textarea class="form-control" rows="8" name="reflection[field][{{$name}}]">{{$field}}</textarea>
        <br/>
    @endforeach

</div>