@extends('layouts.app')

@section('content')

    <div class="container mt-3 pb-3">

        <h2>Voting</h2>

        <hr/>

        <h4>{{ $ballot->name }} <span class="badge badge-secondary">{{ title_case($ballot->type) }}</span></h4>

        <span class="text-muted">
            {{ $ballot->description }}
        </span>

        <span class="text-muted">
            There are many match-ups to vote for. You can vote for as many as you want until you ran out of match-ups, and you can stop and resume at any moment you like!
        </span>

        <style>
            [v-cloak] {
                display: none;
            }
        </style>

        <div id="voting" class="container mt-3" v-bind:class="{ loading: loading }" v-cloak>
            <div class="row" v-if="errored && error !== null">
                <div class="col-12">
                    <div>
                        <div v-if="errorMessage === 'Unauthenticated.'" class="text-center">
                            <div><h2 class="font-weight-light text-muted">You need to be logged-in to vote!</h2></div>

                            <a href="{{ route('login') }}" class="btn btn-outline-primary" style="max-width: 250px">Log-in</a>
                        </div>
                        <div v-else class="alert alert-danger" role="alert">
                            @{{ errorMessage }}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" v-else-if="userHasCompletedBallot()">
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        You have completed this voting ballot. There are no match-ups left!
                    </div>
                </div>
            </div>

            <div v-else>

                <div class="row mb-2">
                    <div class="progress w-100">
                        <div class="progress-bar" role="progressbar" v-bind:style="{ width: votingProgressPercentage + '%'}" v-bind:aria-valuenow="votingProgressPercentage" aria-valuemin="0" aria-valuemax="100">@{{ votingProgressPercentage }}%</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-5 matchup-song-container songA p-3 text-center text-lg-left">

                        <div class="row">
                            <div class="col-12 song-title text-lg-right">
                                <h2>@{{ loading ? "" : voteData.matchup.songs[0].name }}</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 spotify-preview-container">
                                <div class="spotify-preview mb-2 float-lg-right mx-auto">
                                    <iframe id="songA_frame" :src="getSpotifyEmbedURL(voteData.matchup.songs[0].spotify_track_id)" v-if="voteData && voteData.matchup.songs[0].spotify_track_id"
                                            height="80" frameborder="0" allowtransparency="true" allow="encrypted-media" v-on:load="iframeLoaded(0)"
                                            v-show="!loading && !iframesLoading[0]"></iframe>
                                    <div v-else class="container">
                                        No Spotify preview available
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-block btn-outline-secondary float-lg-right vote-btn mx-auto"
                                         v-on:click="sendVote(voteData.matchup.songs[0].id)">Vote</button>
                            </div>
                        </div>

                    </div>
                    <div class="col-12 col-lg-2 versus-text align-self-center text-center">
                        <h2>vs</h2>
                    </div>
                    <div class="col-12 col-lg-5 matchup-song-container songB p-3 text-center text-lg-left">

                        <div class="row">
                            <div class="col-12 song-title">
                                <h2>@{{ loading ? "" : voteData.matchup.songs[1].name }}</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 spotify-preview-container">
                                <div class="spotify-preview mb-2 mx-auto mx-lg-0">
                                    <iframe id="songB_frame" :src="getSpotifyEmbedURL(voteData.matchup.songs[1].spotify_track_id)" v-if="voteData && voteData.matchup.songs[1].spotify_track_id"
                                            height="80" frameborder="0" allowtransparency="true" allow="encrypted-media" v-on:load="iframeLoaded(1)"
                                            v-show="!loading && !iframesLoading[1]"></iframe>
                                    <div v-else class="no-spotify-preview container align-items-center justify-content-center">
                                        <div class="row align-items-center justify-content-center h-100">
                                            No Spotify preview available
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-block btn-outline-secondary vote-btn mx-auto mx-lg-0"
                                         v-on:click="sendVote(voteData.matchup.songs[1].id)">Vote</button>
                            </div>
                        </div>

                    </div>
                </div>

                {{--<div>--}}
                    {{--@{{ JSON.stringify(votingProgress) }} @{{ votingProgressPercentage }}--}}
                {{--</div>--}}

            </div>
        </div>

        @push('scripts')
            <script>
                // http://www.javascriptkit.com/dhtmltutors/sticky-hover-issue-solutions.shtml
                document.addEventListener('touchstart', function addtouchclass(e) {
                    document.documentElement.classList.add('can-touch');
                    document.removeEventListener('touchstart', addtouchclass, false);
                }, false);

                $('.btn').mousedown(function(e){
                    console.log(7);
                    e.preventDefault();
                });

                new Vue({
                    el: '#voting',
                    data: {
                        // storing some stuff out of data so it doesnt update all the time?
                        loading: true,
                        errored: false,
                        error: null,
                        voteData: null,
                        votingProgress: null,
                        iframesLoading: {
                            0: true,
                            1: true
                        },
                        completed: false
                    },
                    mounted () {
                        this.sendVote(null);
                    },
                    methods: {
                        sendVote(votedOn) {
                            this.loading = true;
                            this.iframesLoading[0] = this.iframesLoading[1] = true;

                            var postData = {};

                            if (votedOn !== null) {
                                console.log(this.voteData);

                                postData = {
                                    'voting_matchup_id': this.$data.voteData.matchup.matchup_data.id,
                                    'voted_on': votedOn
                                };
                            }

                            this.voteData = null;

                            return axios.post('/voting-ballots/{{$ballot->id}}/vote-test', postData).then(response => {
                                this.voteData = response.data;
                                this.loading = false;

                                this.votingProgress = response.data.user_voting_progress;
                            })
                            .catch(error => {
                                console.log(error, error.response.data);
                                this.errored = true;
                                this.error = error.response.data;
                                this.loading = false;
                            });
                        },
                        getSpotifyEmbedURL(trackID) {
                            return 'https://open.spotify.com/embed?uri=spotify:track:' + trackID + '&theme=white';
                        },
                        iframeLoaded(index) {
                            console.log(index);
                            this.iframesLoading[index] = false;
                        },
                        userHasCompletedBallot() {


                            var t = this.voteData !== null && this.voteData.user_voting_progress.votes_total === this.voteData.user_voting_progress.votes_submitted;
                            console.log(t);
                            return t;
                        },
                        voteMouseDown(event) {
                            console.log(7, event);
                            event.preventDefault();
                        }
                    },
                    computed: {
                        errorMessage: function () {
                            var msg = '';

                            try {
                                msg = this.error.message;

                                if (this.error.errors) {
                                    Object.keys(this.error.errors).forEach(function(key) {

                                        var keyErrors = this.error.errors[key];
                                        for (var i = 0; i < keyErrors.length; i++) {
                                            msg += ' ' + keyErrors[i];
                                        }

                                        console.log(key, this.error.errors[key]);
                                    }, this, msg);
                                }
                            } catch(e) { }

                            if(msg === '') msg = 'Unexpected error has occured!';

                            return msg;
                        },
                        votingProgressPercentage: function () {
                            if (this.votingProgress) {
                                return ((100 * this.votingProgress.votes_submitted) / this.votingProgress.votes_total).toFixed(1);
                            }

                            return 0;
                        }
                    }
                })
            </script>
        @endpush

    </div>

@endsection