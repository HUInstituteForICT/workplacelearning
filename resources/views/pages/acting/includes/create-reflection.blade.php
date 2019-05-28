<div class="col-md-2 form-group">
    <h4>{{ Lang::get('reflection.reflection') }}</h4>

    <div id="currentReflection" style="text-align: center;">
        {{ __('reflection.none-attached') }}
    </div>

    <br/><br/>


    <div class="fab">
        <span class="fab-action-button" id="actionButton">
            <i class="fab-action-button__icon">
                <img id="actionImage" src="{{ secure_asset('assets/img/plus.svg') }}"/>
            </i>
        </span>
        <ul class="fab-buttons">

            @foreach(\App\ActivityReflection::TYPES as $reflectionType)
                <li class="fab-buttons__item addReflectionType" data-type='{{$reflectionType}}'>
                    <a href="#" class="fab-buttons__link"
                       data-tooltip="{{ \App\ActivityReflection::READABLE_TYPES[$reflectionType] }}">
                        <img class="logout" src="{{ secure_asset('assets/img/'.strtolower($reflectionType).'.svg') }}"/>

                    </a>
                </li>
            @endforeach
        </ul>
    </div>


    <div class=" modal fade" id="reflectionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="reflectionTitle"></h4>
                </div>
                <div class="modal-body">
                    <div id="reflectionFormWrapper"></div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-primary">{{ __('general.hide')  }}</button>
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
    const actionButton = $('#actionButton');
    const actionImage = $('#actionImage');
    const fabButtons = $('.fab-buttons');

    for (let type of reflectionTypeElements) {
        type.onclick = onClickReflectionType
    }

    var reflectionAttached = false;

    function onClickReflectionType(event) {
        reflectionModal.modal('hide');

        const type = event.currentTarget.dataset.type;

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
            currentReflection.append(remover);

            // Update FAB
            actionButton.fadeOut(600, function () {
                actionImage.prop('src', '{{ \secure_asset('assets/img/edit.svg') }}');
                actionButton.click(function () {
                    reflectionModal.modal('show');
                });
                actionButton.fadeIn(600);
            });

            fabButtons.css('display', 'none');

        } else {
            // Update FAB
            actionButton.fadeOut(600, function () {
                actionImage.prop('src', '{{ \secure_asset('assets/img/plus.svg') }}');
                actionButton.unbind('click');
                fabButtons.css('display', 'block');
                actionButton.fadeIn(600);

            });

            currentReflection.html('{{ __('reflection.none-attached') }}');
        }
    }


</script>