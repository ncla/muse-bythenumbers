
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('popper.js');
require('axios/dist/axios.min');
require('datatables.net-bs4/js/dataTables.bootstrap4.min');

import Vue from 'vue'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

console.log('Developed by NCLA – https://ncla.me');

if(document.getElementById('voting')) {
    var ballotId = document.getElementById('voting').dataset.ballotId;

    // http://www.javascriptkit.com/dhtmltutors/sticky-hover-issue-solutions.shtml
    document.addEventListener('touchstart', function addtouchclass(e) {
        document.documentElement.classList.add('can-touch');
        document.removeEventListener('touchstart', addtouchclass, false);
    }, false);

    $('.btn').mousedown(function(e){
        e.preventDefault();
    });

    $('#confirmVoteSkip').find('#confirmVoteSkipBtn').click(function () {
        localStorage.setItem('skipWarning', true);
        voteVue.skipVote();
    });

    var responseReceivedTime = null;

    var voteVue = new Vue({
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
            }
        },
        mounted () {
            this.sendVote();
        },
        methods: {
            sendVote(votedOn) {
                if (responseReceivedTime !== null && (+new Date() - responseReceivedTime) < 500) {
                    return;
                }

                this.loading = true;
                this.iframesLoading[0] = this.iframesLoading[1] = true;

                document.removeEventListener('keyup', this.keyPressed);

                var postData = {};

                var method = typeof votedOn === 'undefined' ? 'get' : 'post';

                if (method === 'post') {
                    postData = {
                        'voting_matchup_id': this.$data.voteData.matchup.matchup_data.id,
                        'voted_on': votedOn,
                        'time': new Date().getTime(),
                        'time_last_response': responseReceivedTime
                    };
                }

                return axios({
                    method: method,
                    url: `/voting-ballots/${ballotId}/vote`,
                    data: postData
                }).then(response => {
                    this.voteData = response.data;
                    this.loading = false;

                    if (this.voteData.matchup.matchup_data !== null) {
                        document.addEventListener('keyup', this.keyPressed);
                    }

                    this.votingProgress = response.data.user_voting_progress;

                    responseReceivedTime = +new Date();

                    try {
                        ga('send', 'pageview', `/voting-ballots/${ballotId}/vote`);
                    } catch(e) {}

                }).catch(error => {
                    console.log(error, error.response.data);
                    this.errored = true;
                    this.error = error.response.data;
                    this.loading = false;
                });
            },
            skipVote() {
                if (localStorage.getItem('skipWarning') === null) {
                    $('#confirmVoteSkip').modal('show');
                } else {
                    this.sendVote(null);
                }
            },
            getSpotifyEmbedURL(trackID) {
                return 'https://open.spotify.com/embed?uri=spotify:track:' + trackID + '&theme=white';
            },
            iframeLoaded(index) {
                this.iframesLoading[index] = false;
            },
            userHasCompletedBallot() {
                return this.voteData !== null && this.voteData.user_voting_progress.votes_total === this.voteData.user_voting_progress.votes_submitted;
            },
            keyPressed(evt) {
                if (evt.keyCode === 37) {
                    this.sendVote(this.voteData.matchup.songs[0].id);
                }

                if (evt.keyCode === 39) {
                    this.sendVote(this.voteData.matchup.songs[1].id);
                }

                if (evt.keyCode === 38 || evt.keyCode === 40) {
                    this.skipVote();
                }
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

                            // console.log(key, this.error.errors[key]);
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
}