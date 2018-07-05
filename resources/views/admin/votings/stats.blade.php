@extends('layouts.app')

@section('content')
        <div class="container mt-3">
                <div class="clearfix"></div>

                @include('flash::message')

                <div class="clearfix"></div>

                <h1 class="pull-left">Voting Stats</h1>

                <table class="table table-bordered table-sm table-hover" id="votings-table">
                        <thead>
                        <th>ID</th>
                        <th>Won</th>
                        <th>Lost</th>
                        <th>Winrate</th>
                        <th>Rank</th>
                        </thead>
                        <tbody>
                        @foreach($ranks as $song)
                                <tr>
                                        <td>{!! $song->song_id !!}</td>
                                        <td>{!! $song->won !!}</td>
                                        <td>{!! $song->lost !!}</td>
                                        <td>{!! $song->winrate !!}</td>
                                        <td>{!! $song->rank !!}</td>
                                </tr>
                        @endforeach
                        </tbody>
                </table>

                <h1 class="pull-left">Vote Distribution</h1>

                <table class="table table-bordered table-sm table-hover" id="votings-table">
                        <thead>
                        <th>ID</th>
                        <th>Song A</th>
                        <th>Song B</th>
                        <th>Count</th>
                        </thead>
                        <tbody>
                        @foreach($matchups as $matchup)
                                <tr>
                                        <td>{!! $matchup->id !!}</td>
                                        <td>{!! $matchup->songA_id !!}</td>
                                        <td>{!! $matchup->songB_id !!}</td>
                                        <td>{!! $matchup->count !!}</td>
                                </tr>
                        @endforeach
                        </tbody>
                </table>

        </div>

@endsection
