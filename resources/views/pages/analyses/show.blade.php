@extends('layout.HUdefault')
@section('title', 'Analyses - Show')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <dl>

                    <dt>Name</dt>
                    <dd class="well">{{ $analysis->name }}</dd>

                    <dt>Cached for</dt>
                    <dd class="well">{{ $analysis->cache_duration }} {{ $analysis->type_time }}</dd>

                    <dt>Query</dt>
                    <dd class="well">{{ $analysis->query }}</dd>

                    <dt>Data</dt>
                    <dd>
                        @if (isset($analysis_result['error']))
                            An error occured during the query:
                            <pre><code>{{ $analysis_result['error'] }}</code></pre>
                        @else
                            <pre><code>{{ json_encode($analysis_result['data'],  JSON_PRETTY_PRINT) }}</code></pre>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
        <div class="row">
            <div class="btn-group btn-group-justified">
                <div class="btn-group">
                    <form action="{{ route('analyses-edit', $analysis->id) }}" method="get" accept-charset="UTF-8">
                        <input type="hidden" name="id" value="{{ $analysis->id }}">
                        <div class="form-group">
                            <button class="btn btn-info" type="submit">Edit analysis</button>
                        </div>
                    </form>
                </div>
                <div class="btn-group">
                    <form action="{{ route('analyses-expire') }}" method="post" accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $analysis->id }}">
                        <div class="form-group">
                            <button class="btn btn-warning" type="submit">Expire data</button>
                        </div>
                    </form>
                </div>
                <div class="btn-group">
                    <form action="{{ route('analyses-destroy', $analysis->id) }}" method="post" accept-charset="UTF-8"
                          id="frmDelete">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <input type="hidden" name="id" value="{{ $analysis->id }}">
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">Delete analysis</button>
                        </div>
                    </form>
                </div>
                <div class="btn-group">
                    <form action="{{ route('analyses-export', $analysis->id) }}" method="get" accept-charset="UTF-8">
                        <div class="form-group">
                            <button class="btn btn-success" type="submit">Export to CSV</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px"
                                                    height="16px"/> Heb je tips of vragen? Geef ze aan ons door!</a>
        </div>
    </div>
    <script>
        var frmDelete = document.getElementById('frmDelete')
        frmDelete.addEventListener('submit', function (e) {
            if (!confirm('Are you sure?')) {
                e.preventDefault()
                return false
            }
        })
    </script>
@stop