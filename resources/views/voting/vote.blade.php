@extends('layouts.app')

@section('content')

    <div class="container mt-3 pb-3">

        <div class="row">
            <div class="col-12">
                @include('flash::message')

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ action('VotingController@index') }}">Voting Ballots</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $ballot->name }}
                            <span class="badge badge-secondary">{{ title_case($ballot->type) }}</span>
                        </li>
                    </ol>
                </nav>

                <hr/>

                <div class="text-muted">
                    <strong>Description: </strong>{{ $ballot->description }}
                </div>

                <span class="text-muted">
                        There are many match-ups to vote for. You can vote for as many as you want until you ran out of match-ups, and you can stop and resume at any moment you like, your progress is tied to your account. The more you vote, the more worth your votes will in the ranking.
                </span>

                <span class="text-muted">
                        You can use arrow keys on your key-board. Left arrow key for the song on the left (or the first one on the screen), right arrow button for the second song (right/bottom, depending on screen size).
                </span>

                <div class="text-muted">
                    <a href="{{ action('VotingController@mystats', ['id' => request()->route('id')]) }}">View My Stats</a> | <a href="{{ action('VotingController@myhistory', ['id' => request()->route('id')]) }}">View My Voting History</a>
                </div>

                <hr/>
            </div>
        </div>

        @auth
            @include('voting/partial/voting_vue')
        @endauth

        @guest
            <div class="container mt-3">
                <div class="row">
                    <div class="col-12">
                        <div>
                            <div class="text-center">
                                <div>
                                    <h2 class="font-weight-light text-muted">You need to be logged-in to vote!</h2>
                                </div>

                                <a href="{{ route('login') }}" class="btn btn-outline-primary" style="max-width: 250px">Log-in</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endguest


    </div>

@endsection