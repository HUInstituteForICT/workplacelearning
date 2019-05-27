<div class="col-md-2 form-group">
    <h4>{{ Lang::get('reflection.reflection') }}</h4>

    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            {{ __('reflection.add-new-reflection') }} <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            @foreach(\App\ActivityReflection::TYPES as $reflectionType)
                <li>
                    <a class="addReflectionType" data-type='{{$reflectionType}}'>
                        {{ \App\ActivityReflection::READABLE_TYPES[$reflectionType] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div id="currentReflection">
        {{ __('reflection.none-attached') }}
    </div>

    <div class="modal fade" id="reflectionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="reflectionTitle"></h4>
                </div>
                <div class="modal-body">
                    <div id="reflectionFormWrapper"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    const reflectionUrl = '{{ route('render-reflection-type', ['type' => 'type-param']) }}';
    const reflectionTypeElements = document.getElementsByClassName('addReflectionType');
    const formWrapper = document.getElementById('reflectionFormWrapper');
    const reflectionTitleElement = document.getElementById('reflectionTitle');
    const reflectionModal = $('#reflectionModal');
    const currentReflection = $('#currentReflection');

    for (let type of reflectionTypeElements) {
        type.onclick = onClickReflectionType
    }

    var reflectionAttached = false;

    function onClickReflectionType(event) {
        reflectionModal.modal('hide');

        const type = event.target.dataset.type;

        const url = reflectionUrl.replace('type-param', type);
        fetch(url).then(function (response) {
            return response.text()
        }).then(function (content) {
            renderReflectionForm(content, type)
        })
    }

    function renderReflectionForm(reflectionForm, type) {
        reflectionModal.modal('show');
        formWrapper.innerHTML = reflectionForm;
        reflectionTitleElement.innerText = '{{__('reflection.reflection')}}: ' + type;
        reflectionAttached = true;

        updateCurrentReflectionText(type);
    }

    function updateCurrentReflectionText(type) {
        if (reflectionAttached) {

            const remover = $('<a></a>');
            remover.text('{{__('reflection.remove')}}');
            remover.click(function () {
                formWrapper.innerHTML = '';
                reflectionAttached = false;
                updateCurrentReflectionText(null);
            });

            currentReflection.html('{{__('reflection.reflection')}}: ' + type + ' - ');
            currentReflection.append(remover)
        } else {
            currentReflection.html('{{ __('reflection.none-attached') }}');
        }
    }


</script>