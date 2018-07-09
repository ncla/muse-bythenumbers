@extends('layouts.app')

@section('content')

    <div class="container mt-3 pb-3">

        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <h2>Personal Voting Statistics</h2>
                </div>
                <div class="float-right">
                    <a href="{{ action('VotingController@show', ['id' => request()->route('id')]) }}" class="btn btn-primary pull-right btn-sm">Back</a>
                </div>
            </div>
        </div>

        <hr/>

        <h4>{{ $ballot->name }} <span class="badge badge-secondary">{{ title_case($ballot->type) }}</span></h4>

        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="personalStats">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Winrate</th>
                        <th scope="col">Rank</th>
                        <th scope="col">Won</th>
                        <th scope="col">Lost</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($stats as $song)
                    <tr>
                        <td>{{ $song->name }}</td>
                        <td>{{ round($song->winrate, 2) }}%</td>
                        <td>{{ round($song->rank, 2) }}</td>
                        <td>{{ $song->won }}</td>
                        <td>{{ $song->lost }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        @push('scripts')
            <script>
                $(document).ready(function () {
                    $('#personalStats').DataTable({
                        aLengthMenu: [
                            [25, 50, 100, 200, -1],
                            [25, 50, 100, 200, "All"]
                        ],
                        iDisplayLength: -1
                    });
                });
            </script>
        @endpush

    </div>

@endsection