<div class="modal-header">
    <h4 class="modal-title">@lang('querybuilder.step2.builder')</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <div class="form-group">
            <label for="analysis_entity">@lang('querybuilder.step2.entity')</label>
            <select class="form-control" name="analysis_entity" id="analysis_entity">
                @foreach($models as $model)
                    <option {{ isset($data['analysis_entity']) && $data['analysis_entity'] == $model ? "selected" : "" }} value="{{ $model }}">{{ __('querybuilder.'.$model) }}</option>
                @endforeach
            </select>
        </div>
        <p style="font-weight: bold;">@lang('querybuilder.step2.relations')</p>
        <div class="relations">
            @foreach($relations as $relation => $value)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="analysis_relation[]" id="analysis_relations_{{ $relation }}" value="{{ $relation }}"
                            {{ isset($data['analysis_relation']) && in_array($relation, $data['analysis_relation']) ? "checked" : "" }}>
                    <label class="form-check-label" for="analysis_relations_{{ $relation }}">
                        {{ $value }}
                    </label>
                </div>
            @endforeach
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(1);">@lang('querybuilder.previous')</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(3);">@lang('querybuilder.next')</button>
</div>