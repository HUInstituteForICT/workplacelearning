@extends('layout.HUdefault')
@section('title', 'Analyses - Show')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1>{{ Lang::get('analyses.title') }}</h1>

                <div class="row">
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group">
                            <form action="{{ route('analytics-edit', $analysis->id) }}" method="get" accept-charset="UTF-8">
                                <input type="hidden" name="id" value="{{ $analysis->id }}">
                                <div class="form-group">
                                    <button class="btn btn-info" type="submit">{{ Lang::get('dashboard.analysis-edit') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="btn-group">
                            <form action="{{ route('analytics-expire') }}" method="post" accept-charset="UTF-8">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $analysis->id }}">
                                <div class="form-group">
                                    <button class="btn btn-warning" type="submit">{{ Lang::get('dashboard.expire') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="btn-group">
                            <form action="{{ route('analytics-destroy', $analysis->id) }}" method="post" accept-charset="UTF-8"
                                  id="frmDelete">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <input type="hidden" name="id" value="{{ $analysis->id }}">
                                <div class="form-group">
                                    <button class="btn btn-danger" type="submit">{{ Lang::get('dashboard.analysis-delete') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="btn-group">
                            <form action="{{ route('analytics-export', $analysis->id) }}" method="get" accept-charset="UTF-8">
                                <div class="form-group">
                                    <button class="btn btn-success" type="submit">{{ Lang::get('dashboard.analysis-export-csv') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                <dl>

                    <dt>{{ Lang::get('dashboard.name') }}</dt>
                    <dd class="well">{{ $analysis->name }}</dd>

                    <dt>{{ Lang::get('dashboard.cache-for') }}</dt>
                    <dd class="well">{{ $analysis->cache_duration }} {{ $analysis->type_time }}</dd>

                    <dt>Query</dt>
                    <dd class="well">{{ $analysis->query }}</dd>

                    <dt>Data</dt>
                    <dd>
                        @if (isset($analysis_result['error']))
                            {{ Lang::get('dashboard.query-error') }}:
                            <pre><code>{{ $analysis_result['error'] }}</code></pre>
                        @else
                            <pre><code>{{ json_encode($analysis_result['data'],  JSON_PRETTY_PRINT) }}</code></pre>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

    <script>
        var frmDelete = document.getElementById('frmDelete');
        frmDelete.addEventListener('submit', function (e) {
            if (!confirm('{{ Lang::get('dashboard.warning') }}')) {
                e.preventDefault();
                return false
            }
        })
    </script>
@stop