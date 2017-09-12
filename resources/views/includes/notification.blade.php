@if(session()->has('notification') || count($errors) > 0 || session()->has('success'))
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('notification'))
                    <div class="alert alert-notice">
                        <span>{{ Lang::get('elements.alerts.notice') }}: </span>{!! session('notification') !!}
                    </div>
                @endif
                @if(count($errors) > 0 || session()->has('success'))
                    <div class="alert alert-{{ (session()->has('success')) ? 'success' : 'error' }}">
                        <span>{{ Lang::get('elements.alerts.'.((session()->has('success') ? 'success' : 'error'))) }}
                            : </span>{{ (session()->has('success')) ? session('success') : $errors->first() }}
                    </div>
                @endif
            </div>
        </div>
@endif
