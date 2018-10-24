@extends('layouts.app')

@section('content')
        <div class="container mt-3">
                <div class="clearfix"></div>

                @include('flash::message')

                <div class="clearfix"></div>

                <div class="row">
                        <div class="col-12">
                                <h1 class="pb-0 mb-0">Precalculated Voting Stats</h1>
                                @if($precalculated_results)
                                        <small>Pre-calculated on: {{ $precalculated_results->created_at }} | Public: {{ $precalculated_results->public }}</small>
                                @else
                                        <small>No precalculated stats available</small>
                                @endif
                        </div>
                </div>

                @if($precalculated_results)
                <div class="row">
                        <div class="col-12">

                                <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="stats">
                                        <thead>
                                        <th>Name</th>
                                        <th>Total Votes</th>
                                        <th>Won</th>
                                        <th>Lost</th>
                                        <th>Winrate</th>
                                        <th>Rank</th>
                                        </thead>
                                        <tbody>
                                        @foreach($precalculated_results->songResults as $result)
                                                <tr>
                                                        <td>{!! $result->song->name !!}</td>
                                                        <td>{!! $result->total_votes !!}</td>
                                                        <td>{!! $result->votes_won !!}</td>
                                                        <td>{!! $result->votes_lost !!}</td>
                                                        <td>{!! round($result->winrate, 2) !!}%</td>
                                                        <td>{!! round($result->elo_rank, 2) !!}</td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                </table>

                        </div>
                </div>
                @endif

                <div class="row">
                        <div class="col-12">
                                <h1 class="pull-left">Voting Stats</h1>
                        </div>
                </div>

                <div class="row">
                        <div class="col-12">

                                <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="stats">
                                        <thead>
                                        <th>Name</th>
                                        <th>Total Votes</th>
                                        <th>Won</th>
                                        <th>Lost</th>
                                        <th>Winrate</th>
                                        <th>Rank</th>
                                        </thead>
                                        <tbody>
                                        @foreach($ranks as $song)
                                                <tr>
                                                        <td>{!! $song->name !!}</td>
                                                        <td>{!! $song->totalVotes !!}</td>
                                                        <td>{!! $song->won !!}</td>
                                                        <td>{!! $song->lost !!}</td>
                                                        <td>{!! round($song->winrate, 2) !!}%</td>
                                                        <td>{!! round($song->rank, 2) !!}</td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                </table>

                        </div>
                </div>


                <div class="row">
                        <h1 class="pull-left">User Vote Counts</h1>
                </div>

                <div class="row">
                        <div class="col-12">

                                <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="stats">
                                        <thead>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Votes</th>
                                        </thead>
                                        <tbody>
                                        @foreach($votes_user as $user)
                                                <tr>
                                                        <td>{!! $user->user_id !!}</td>
                                                        <td>{{ $user->username }}</td>
                                                        <td>{{ $user->count }}</td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                </table>

                        </div>
                </div>

                @push('scripts')
                        <script>
                            $(document).ready(function () {
                                $('#stats').DataTable({
                                    aLengthMenu: [
                                        [25, 50, 100, 200, -1],
                                        [25, 50, 100, 200, "All"]
                                    ],
                                    iDisplayLength: -1
                                });
                            });
                        </script>
                @endpush

                <div class="row">
                        <h1 class="pull-left">Matchup Count Grouped By Vote Count</h1>
                </div>


                <div class="row">
                        <div class="col-12">
                                <p>This is a summary of the Vote Distribution table. If you want to see all match-ups, see Vote Distribution table. This includes skipped votes.</p>

                                <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="votings-table">
                                        <thead>
                                        <th>Matchup Count</th>
                                        <th>Vote Count</th>
                                        </thead>
                                        <tbody>
                                        @foreach($matchupVotesDistribution as $entry)
                                                <tr>
                                                        <td>{!! $entry->matchUpCount !!}</td>
                                                        <td>{!! $entry->voteCount !!}</td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                </table>
                        </div>
                </div>

                <div class="row">
                        <h1 class="pull-left">Vote Distribution</h1>
                </div>


                <div class="row">
                        <div class="col-12">
                                <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="votings-table">
                                        <thead>
                                        <th>ID</th>
                                        <th>Song A</th>
                                        <th>Song B</th>
                                        <th>Count</th>
                                        </thead>
                                        <tbody>
                                        @foreach($matchupDistribution as $matchup)
                                                <tr>
                                                        <td>{!! $matchup->id !!}</td>
                                                        <td>{{ $matchup->songA_name }} [{!! $matchup->songA_id !!}]</td>
                                                        <td>{{ $matchup->songB_name }} [{!! $matchup->songB_id !!}]</td>
                                                        <td>{!! $matchup->count !!}</td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                </table>
                        </div>
                </div>

        </div>

@endsection
