import Vue from 'vue'

window.Vue = Vue;

if (document.getElementById('song-list')) {
    new Vue({
        el: '#song-list',
        data: {
            songsAvailable: songsAvailable,
            songsSelected: songsSelected
        },
        computed: {
            orderedSongsAvailable: function () {
                return _.orderBy(this.songsAvailable, 'name')
            }
        },
        methods: {
            addToSongList: function(song, index) {
                this.songsSelected.push(song);
                this.songsAvailable = _.filter(this.songsAvailable, function(o) {
                    return o.id !== song.id;
                });
            },
            removeFromSongList: function(song, index) {
                this.songsAvailable.push(song);
                this.songsSelected = _.filter(this.songsSelected, function(o) {
                    return o.id !== song.id;
                });
            },
            addAllAvailable: function() {
                this.songsAvailable.forEach(function(element) {
                    this.songsSelected.push(element);
                }, this);
                this.songsAvailable = [];
            },
            removeAllSelected: function () {
                this.songsSelected.forEach(function(element) {
                    this.songsAvailable.push(element);
                }, this);
                this.songsSelected = [];
            }
        }
    });
}

// new Vue({
//     el: '#matchups-list',
//     data: {
//         isShown: false
//     },
//     methods: {
//         toggle: function () {
//             this.isShown = !this.isShown;
//         }
//     }
// });