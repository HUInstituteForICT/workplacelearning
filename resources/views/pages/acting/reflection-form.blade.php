<div>

    @foreach($fields as $name => $field)

        <strong>{{ __('reflection.fields.' . $type .'.'.$name) }}</strong>
        <br/>

        <textarea class="form-control" rows="10" name="reflection[field][{{$name}}]">{{$field}}</textarea>

    @endforeach

</div>