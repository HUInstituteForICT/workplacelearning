@extends('layout.HUdefault')
@section('title', __('charts.title'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ __('charts.title') }}</h1>
                <ul>
                    @forelse($analyses as $analysis)
                        <li>
                            <a href="{{ route('analytics-show', $analysis->id) }}">{{ $analysis->name }}</a>
                            @forelse($analysis->charts as $chart)
                                <ul>
                                    <li>
                                        <a href="{{ route('charts.show', $chart->id) }}">{{ $chart->label }}</a>
                                        <form action="{{ route('charts.destroy', $chart->id) }}"
                                              class="frmDelete"
                                              style="display: inline-block;" method="post" accept-charset="UTF-8">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            [
                                            <button class="btn-link">{{ __('dashboard.delete') }}</button>
                                            ]
                                        </form>
                                    </li>
                                </ul>
                        </li>
                    @empty
                    @endforelse
                    @empty
                        <li>
                            <p>{{ __('dashboard.no-charts') }}</p>
                        </li>
                    @endforelse
                </ul>
                <a href="{{ route('charts.create') }}" class="btn btn-primary">{{ __('general.create') }}</a>
            </div>
        </div>
    </div>
    <script>
        (function () {
            $('.frmDelete').on('submit', function (e) {
                if (!confirm('{{__('dashboard.warning')}}')) {
                    e.preventDefault();
                    return false
                }
            })
        })()
    </script>
@stop