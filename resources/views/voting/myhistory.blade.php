@extends('layouts.app')

@section('content')

    <div class="container mt-3 pb-3 user-voting-history">

        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ action('VotingController@index') }}">Voting Ballots</a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ action('VotingController@show', ['id' => request()->route('id')]) }}">{{ $ballot->name }}</a> <span class="badge badge-secondary">{{ title_case($ballot->type) }}</span>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            My Voting History
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="personalHistory">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Song A</th>
                    <th scope="col">Song B</th>
                    <th scope="col">Timestamp</th>
                </tr>
                </thead>
                <tbody>
                @foreach($history as $entry)
                    <tr class="
                        @if($entry->winner_song_id === null) skipped-vote @endif
                    ">
                        <td @if($entry->songA_id === $entry->winner_song_id) class="font-weight-bold" @endif>
                            {{ $entry->songA_name }}
                        </td>
                        <td @if($entry->songB_id === $entry->winner_song_id) class="font-weight-bold" @endif>
                            {{ $entry->songB_name }}
                        </td>
                        <td>{{ $entry->created_at }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        @push('scripts')
            <script>
                $(document).ready(function () {
                    $('#personalHistory').DataTable({
                        aLengthMenu: [
                            [25, 50, 100, 200, -1],
                            [25, 50, 100, 200, "All"]
                        ],
                        iDisplayLength: 25,
                        "order": [
                            [2, "desc"]
                        ]
                    });
                });
            </script>
        @endpush

    </div>

@endsection