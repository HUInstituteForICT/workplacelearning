<div class="col-md-3 fullReflection" @if(!$reflectionSettings['fullReflection']) style="display: none;" @endif>
    <h4>{{ __('reflection.full_reflection') }}<span id="currentReflection"></span></h4>

    <div class="fab">
        <span class="fab-action-button" id="actionButton">
            <i class="fab-action-button__icon">
                <img id="actionImage" class="state-add" src="{{ secure_asset('assets/img/plus.svg') }}"/>
            </i>
        </span>
        <ul class="fab-buttons">

            @foreach($orderedReflectionTypes as $reflectionType)
                <li class="fab-buttons__item addReflectionType" data-type='{{$reflectionType}}'>
                    <a class="fab-buttons__link"
                       data-tooltip="{{ \App\Reflection\Models\ActivityReflection::READABLE_TYPES[$reflectionType] }}">
                        <img src="{{ secure_asset('assets/img/'.strtolower($reflectionType).'.svg') }}"/>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>


    <div class="modal fade" id="reflectionModal">
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

                    <div style="display: flex; justify-content: space-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="detachReflection()">{{__('reflection.remove')}}</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal">{{ __('general.hide')  }}</button>
                    </div>

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
        reflectionAttached = true;

        updateCurrentReflectionText(type);
    }

    function detachReflection() {
        formWrapper.innerHTML = '';
        reflectionAttached = false;
        updateCurrentReflectionText(null);
    }

    function updateCurrentReflectionText(type) {
        if (reflectionAttached) {
            reflectionTitleElement.innerText = '{{__('reflection.reflection')}}: ' + type;

            currentReflection.text(': ' + type);

            // Update FAB
            actionButton.fadeOut(600, function () {
                actionImage.prop('src', '{{ \secure_asset('assets/img/edit.svg') }}');
                actionImage.removeClass('state-add');
                actionButton.click(function () {
                    reflectionModal.modal('show');
                });
                actionButton.fadeIn(600);
            });

            fabButtons.css('display', 'none');

        } else {
            // Update FAB
            actionButton.unbind('click');
            actionButton.fadeOut(600, function () {
                actionImage.prop('src', '{{ \secure_asset('assets/img/plus.svg') }}');
                actionImage.addClass('state-add');
                fabButtons.css('display', 'block');
                actionButton.fadeIn(600);

            });

            currentReflection.text('');
        }
    }


</script>
