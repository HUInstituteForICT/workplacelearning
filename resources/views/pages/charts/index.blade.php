@extends('layout.HUdefault')
@section('title', Lang::get('charts.title'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ Lang::get('charts.title') }}</h1>
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
                                            <button class="btn-link">delete</button>
                                            ]
                                        </form>
                                    </li>
                                </ul>
                        </li>
                    @empty
                    @endforelse
                    @empty
                        <li>
                            <p>There are no charts.</p>
                        </li>
                    @endforelse
                </ul>
                <a href="{{ route('charts.create') }}" class="btn btn-primary">Create</a>
            </div>
        </div>
    </div>
    <script>
      (function () {
        $('.frmDelete').on('submit', function (e) {
          if (!confirm('Are you sure?')) {
            e.preventDefault()
            return false
          }
        })
      })()
    </script>
@stop