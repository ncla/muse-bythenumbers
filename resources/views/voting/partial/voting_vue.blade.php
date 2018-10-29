<style>
    [v-cloak] {
        display: none;
    }
</style>

<div class="modal fade" id="confirmVoteSkip" tabindex="-1" role="dialog" aria-labelledby="confirmVoteSkipLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                If you decide to skip voting for a match-up, then the vote skip will be recorded in the database, and you will no longer be able to vote on the match-up you decide to skip.

                Skipped votes do not contribute to the statistics generated on this website.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmVoteSkipBtn" data-dismiss="modal">I understand</button>
            </div>
        </div>
    </div>
</div>

<div id="voting" data-ballot-id="{{ $ballot->id }}" class="container mt-1" v-bind:class="{ loading: loading }" v-cloak>
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
            <div class="progress w-100 position-relative">
                <div class="progress-bar" role="progressbar" v-bind:style="{ width: votingProgressPercentage + '%'}" v-bind:aria-valuenow="votingProgressPercentage" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="justify-content-center d-flex position-absolute w-100 h-100 align-items-center">
                    <div class="d-flex">
                                <span class="voting-progress-text">
                                    @{{ votingProgressPercentage }}% complete
                                    <span v-if="votingProgress !== null"> (@{{ votingProgress.votes_submitted }} / @{{ votingProgress.votes_total }})</span>
                                </span>
                    </div>
                </div>
                {{--<span></span>--}}
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-5 matchup-song-container songA p-3 text-center text-lg-left">

                <div class="row">
                    <div class="col-12 song-title text-lg-right">
                        <h2>@{{ loading ? "" : voteData.matchup.songs[0].name }}</h2>
                    </div>

                    <div class="col-12 spotify-preview-container">
                        <div class="spotify-preview mb-2 float-lg-right mx-auto">
                            <iframe id="songA_frame" :src="getSpotifyEmbedURL(voteData.matchup.songs[0].spotify_track_id)" v-if="voteData && voteData.matchup.songs[0].spotify_track_id"
                                    height="80" frameborder="0" allowtransparency="true" allow="encrypted-media" v-on:load="iframeLoaded(0)"
                                    v-show="!loading && !iframesLoading[0]"></iframe>
                            <div v-else class="no-spotify-preview container align-items-center justify-content-center">
                                <div class="row align-items-center justify-content-center h-100">
                                    No Spotify preview available
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="button" class="btn btn-block btn-outline-secondary float-lg-right vote-btn mx-auto"
                                v-on:click="sendVote(voteData.matchup.songs[0].id)">Vote</button>
                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-2 versus-text align-self-center text-center">
                <button type="button" class="btn btn-outline-secondary vote-btn mx-auto my-3 my-lg-0" v-on:click="skipVote()">Skip</button>
            </div>

            <div class="col-12 col-lg-5 matchup-song-container songB p-3 text-center text-lg-left">

                <div class="row">
                    <div class="col-12 song-title order-1 order-lg-0">
                        <h2>@{{ loading ? "" : voteData.matchup.songs[1].name }}</h2>
                    </div>
                    <div class="col-12 spotify-preview-container order-2 order-lg-1">
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

                    <div class="col-12 order-0 order-lg-2">
                        <button type="button" class="btn btn-block btn-outline-secondary vote-btn mx-auto mx-lg-0 mb-2 mb-lg-0"
                                v-on:click="sendVote(voteData.matchup.songs[1].id)">Vote</button>
                    </div>
                </div>



            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>

    </script>
@endpush