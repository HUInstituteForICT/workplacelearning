@if(session()->has('notification') || count($errors) > 0 || session()->has('success') || session()->has('error') || session()->has('no-internship'))
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('notification'))
                    <div class="alert alert-notice">
                        <span>{{ __('elements.alerts.notice') }}: </span>{!! session('notification') !!}
                    </div>
                @endif
                @if(count($errors) > 0 || session()->has('success'))
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ __('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}
                            : </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                @endif
                @if (session()->has('no-internship'))
                    <div class="alert alert-error">
                        <span>{{ __('elements.alerts.warning') }}: </span>{!! session('no-internship') !!}
                    </div>
                @endif
            </div>
        </div>
@endif
