@extends('layout.HUdefault')

@section('title')
    Admin dashboard
@stop
@section('content')
    <div class="container-fluid">
                <div class="panel-body">
                    <hr/>
                        @if ($errors->any())
                            <div class="modal-header">
                                <h4 class="modal-title text-center">Oeps!</h4>
                            </div>
                            <div class="modal-body">
                                <p class="text-center">{{ __('activity.import-error-message') }}</p>
                            </div>
                        @elseif(!empty($successMsg))
                            <div>
                                <div class="modal-header">
                                    <h4 class="modal-title text-center">{{ __('activity.import-succes-header-message') }}</h4>
                                </div>
                                <div class="modal-body alert-success">
                                    <p class="text-center">{{ __('activity.import-succes-message') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('js.linking')
@stop