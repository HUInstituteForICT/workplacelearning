@extends('layout.HUdefault')
@section('title')
    React Logs
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Log</th>
                        <th>Fixed</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>
                                <pre>{!! nl2br(str_replace('\n', '<br>    ', json_encode(json_decode($log->log), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) !!}
                                </pre>
                            </td>
                            <td>{{ $log->fixed ? 'yes' : 'no' }}</td>
                            <td>{{ date('d-m-Y', $log->created_at->timestamp) }}</td>
                            <td>
                                @if(!$log->fixed)
                                    <a class="btn btn-primary"
                                       href="{{ route('fix-reactlog', ['reactLog' => $log->id]) }}">Fix</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop