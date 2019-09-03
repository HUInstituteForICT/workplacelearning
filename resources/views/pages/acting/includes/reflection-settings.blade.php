<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <i class="icon fa fa-cog"></i>
        {{ __('activity.reflection-methods') }}
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li>
            <a class="disabled">{{ __('activity.reflection-methods-help') }}</a>
        </li>
        <li class="divider"></li>
        <li><a class="reflectionSettingToggle" data-reflection-setting="shortReflection">
                @if($reflectionSettings['shortReflection'])
                    <i class="shortReflectionIcon icon fa fa-check"></i>
                @else
                    <i class="shortReflectionIcon icon fa fa-close"></i>
                @endif
                - {{ __('reflection.short_reflection') }}
            </a></li>
        <li><a class="reflectionSettingToggle" data-reflection-setting="fullReflection">
                @if($reflectionSettings['fullReflection'])
                    <i class="fullReflectionIcon icon fa fa-check"></i>
                @else
                    <i class="fullReflectionIcon icon fa fa-close"></i>
                @endif
                - {{ __('reflection.full_reflection') }}
            </a></li>
    </ul>
</div>

<script>
    var reflectionSettings = {!! json_encode($reflectionSettings) !!}

    $('.reflectionSettingToggle').click(updateReflectionSettings);

    /**
     *
     * @param e {MouseEvent}
     */
    function updateReflectionSettings(e) {
        let setting = e.target.dataset['reflectionSetting'];
        reflectionSettings[setting] = !reflectionSettings[setting];
        axios.post('{{ route('acting-store-reflection-user-settings') }}', {reflectionSettings})
            .then(function(response) {
                updateReflectionSettingsUI();
                updateReflectionUI();
            });
    }

    function updateReflectionSettingsUI() {
        Object.keys(reflectionSettings).forEach(function (setting) {
            let settingIcon = $('i.' + setting + 'Icon');
            if (reflectionSettings[setting]) {
                settingIcon.addClass('fa-check');
                settingIcon.removeClass('fa-close');
            } else {
                settingIcon.addClass('fa-close');
                settingIcon.removeClass('fa-check');
            }
        });
    }

    function updateReflectionUI() {
        Object.keys(reflectionSettings).forEach(function (setting) {
            let reflectionContainer = $('div.' + setting);

            if(reflectionSettings[setting]) {
                reflectionContainer.show();
            } else {
                reflectionContainer.hide();
            }
        });
    }

    (function() {
        updateReflectionUI()
    })()


</script>