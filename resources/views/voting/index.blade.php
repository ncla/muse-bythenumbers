@extends('layouts.app')

@section('content')

    <div class="container mt-3">
        <div class="row mb-2">

            @foreach($votings as $voting)
            <div class="col-12">
                <div class="card flex-md-row mb-4 box-shadow h-md-250">
                    <div class="card-body d-flex flex-column">
                        <strong class="d-inline-block mb-2 text-primary">{{ title_case($voting->type) }}</strong>
                        <h3 class="mb-0">
                            <a class="text-dark" href="{{ action('VotingController@show', [$voting->id]) }}">{{ $voting->name }}</a>
                        </h3>
                        <div class="mb-1 text-muted">
                            <span data-toggle="tooltip" data-placement="top" title="{{ $voting->created_at }}">{{ \Carbon\Carbon::parse($voting->created_at)->toFormattedDateString() }}</span> –
                            <span data-toggle="tooltip" data-placement="top" title="{{ $voting->expires_on }}">{{ \Carbon\Carbon::parse($voting->expires_on)->toFormattedDateString() }}</span>
                        </div>

                        <p class="card-text mb-auto">{{ $voting->description }}</p>

                        <div>

                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="btn-group">
                                <a href="{{ action('VotingController@show', [$voting->id]) }}" class="btn btn-outline-secondary btn-sm">Vote</a>
                                @can('manage-voting-ballots')
                                    <a href="{{ action('Admin\VotingController@edit', [$voting->id]) }}" class="btn btn-outline-danger btn-sm">Administrate</a>
                                @endcan
                                @can('manage-voting-ballots')
                                    <a href="{{ action('Admin\VotingController@showStats', [$voting->id]) }}" class="btn btn-outline-danger btn-sm">Statistics</a>
                                @endcan
                            </div>

                            <div class="pull-right">
                                <span>{{ count($voting->matchups) }} {{ str_plural('matchups', count($voting->songs)) }}</span>
                                |
                                <span>{{ count($voting->songs) }} {{ str_plural('song', count($voting->songs)) }}</span>
                            </div>
                        </div>
                    </div>
                    {{--<img class="card-img-right flex-auto d-none d-md-block" data-src="holder.js/200x250?theme=thumb" alt="Card image cap">--}}
                </div>
            </div>
            @endforeach

        </div>
    </div>

    @push('scripts')
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
        </script>
    @endpush

@endsection