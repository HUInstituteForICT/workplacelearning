<?php
use App\Repository\SearchFilter;
/** @var SearchFilter $filter */
?>
<div class="col-md-3">
    <div class="form-group">
        <label>
            {{ __('filters.' . $filter->getProperty()) }}

            <select class="form-control" name="filter[{{ $filter->getProperty() }}]">
                <option value="-1">-</option>

                {{--Check if we use groups for the select--}}
                @if(isset($filter->getOptions()['enabled'], $filter->getOptions()['disabled']))

                    <optgroup label="Enabled">
                        @foreach($filter->getOptions()['enabled'] as $key => $value)

                            <option
                                    @if((int) request('filter.' . $filter->getProperty()) === (int) $key) selected @endif
                            value="{{ $key }}">{{ __($value) }}</option>

                        @endforeach
                    </optgroup>

                    <optgroup label="Disabled">
                        @foreach($filter->getOptions()['disabled'] as $key => $value)

                            <option
                                    @if((int) request('filter.' . $filter->getProperty(), -1) === (int) $key) selected @endif
                            value="{{ $key }}">{{ __($value) }}</option>

                        @endforeach
                    </optgroup>

                {{-- If not using groups, render simple select --}}
                @else

                    @foreach($filter->getOptions() as $key => $value)

                        <option
                                @if((int) request('filter.' . $filter->getProperty(), -1) === (int) $key) selected @endif
                        value="{{ $key }}">{{ __($value) }}</option>

                    @endforeach

                @endif
            </select>
        </label>
    </div>
</div>