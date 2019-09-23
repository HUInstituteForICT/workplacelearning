<div class="col-md-3">
    <div class="form-group">
        <label>
            {{ __('filters.' . $filterName) }}
            <input class="form-control" type="text"
                   value="{{ request('filter.' . $filterName, '') }}"
                   name="filter[{{ $filterName }}]"/>
        </label>
    </div>
</div>