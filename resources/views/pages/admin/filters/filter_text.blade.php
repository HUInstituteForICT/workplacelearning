<div class="col-md-3">
    <div class="form-group">
        <label>
            {{ __('filters.' . $filter->getProperty()) }}
            <input class="form-control" type="text"
                   value="{{ request('filter.' . $filter->getProperty(), '') }}"
                   name="filter[{{ $filter->getProperty() }}]"/>
        </label>
    </div>
</div>