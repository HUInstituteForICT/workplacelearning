@extends('layout.HUdefault')

@section('title')
    Admin dashboard
@stop
@section('content')

    <div class="container-fluid">

        <h1>{{ __('activity.activity-import-overzicht') }}</h1>
        <div class="row">


            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <h3 id="teacher-overview"
                            >{{ __('activity.upload-activities') }}</h3>
                        <button id="import-btn" class="btn btn-primary" style="float: right;" data-target="#CSV-Import-Modal"
                                data-toggle="modal">Upload CSV
                        </button>
                        <hr/>
                        @if ($errors->any())
                            <div class="modal-header">
                                <h4 class="modal-title text-center">Oeps!</h4>
                            </div>
                            <div class="modal-body">
                                <p class="text-center">{{ __('activity.import-error-message') }}</p>
                            </div>
                            <div class="alert alert-danger">

                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
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


        <!-- Modal for CSV Import Func-->
        <div class="modal fade" id="CSV-Import-Modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ __('activity.upload-csv') }}</h4>

                        <div class="modal-body">
                            {!! Form::open(array('url' =>  route('activity-import-save'),
                            'files' => true,'enctype'=>'multipart/form-data')) !!}

                            <div class="form-group">
                                {!! Form::file('csv_file', $attributes = array()) !!}
                            </div>
                            <button type="button" data-dismiss="modal">{{ __('activity.cancel-upload') }}</button>
                            {{ Form::submit('Upload', array('class' => 'btn btn-info', 'style' => 'float: right;', 'id' => 'coupleButton')) }}
                            {{ Form::close() }}

                        </div>


                    </div>

                </div>
            </div>
        </div>

    <!-- Succes Modal-->
    <div class="modal fade" id="CSV-Import-Succes-Modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">{{ __('activity.import-succes-header-message') }}</h4>
                </div>
                <div class="modal-body">
                    <p class="text-center">{{ __('activity.import-succes-message') }}</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-block" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal-->
    <div class="modal fade" id="CSV-Import-Error-Modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    {{--                    <div class="icon-box">--}}
                    {{--                        <i class="icon">&#xE876;</i>--}}
                    {{--                    </div>--}}
                    <h4 class="modal-title text-center">Oeps!</h4>
                </div>
                <div class="modal-body">
                    <p class="text-center">{{ __('activity.import-error-message') }}</p>
                </div>
                <div>
                    <p>Hier komen dan alle errors</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-block" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    @include('js.linking')



@stop