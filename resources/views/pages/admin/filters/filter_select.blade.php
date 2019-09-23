<div class="col-md-3">
    <div class="form-group">
        <label style="width: 100%;">
            {{ __('filters.' . $filterName) }}

            <select class="form-control" name="filter[{{ $filterName }}]">
                <option value="-1">-</option>

                {{--Check if we use groups for the select--}}
                @if(isset($filterOptions['options']['enabled'], $filterOptions['options']['disabled']))

                    <optgroup label="Enabled">
                        @foreach($filterOptions['options']['enabled'] as $key => $value)

                            <option
                                    @if((int) request('filter.' . $filterName) === (int) $key) selected @endif
                            value="{{ $key }}">{{ __($value) }}</option>

                        @endforeach
                    </optgroup>

                    <optgroup label="Disabled">
                        @foreach($filterOptions['options']['disabled'] as $key => $value)

                            <option
                                    @if((int) request('filter.' . $filterName) === (int) $key) selected @endif
                            value="{{ $key }}">{{ __($value) }}</option>

                        @endforeach
                    </optgroup>

                {{-- If not using groups, render simple select --}}
                @else

                    @foreach($filterOptions['options'] as $key => $value)

                        <option
                                @if((int) request('filter.' . $filterName) === (int) $key) selected @endif
                        value="{{ $key }}">{{ __($value) }}</option>

                    @endforeach

                @endif
            </select>
        </label>
    </div>
</div>