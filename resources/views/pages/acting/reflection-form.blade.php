<div style="max-height: 80vh; overflow-y: scroll">
        <input type="hidden" name="reflection[type]" value="{{$type}}" />
    @foreach($fields as $name => $field)

        <h4>{{ __('reflection.fields.' . $type .'.'.$name) }}</h4>
        <textarea class="form-control" rows="8" name="reflection[field][{{$name}}]">{{$field}}</textarea>
        <br/>
    @endforeach

</div>