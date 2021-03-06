@php
    SEOMeta::setTitle('About');
@endphp

@extends('layouts.app')

@section('content')
    <div class="container mt-3">

        <div class="row">
            <div class="col-12">
                <h1>About</h1>

                <p>
                    ByTheNumbers is made by ncla (/u/iamncla) and is suppose to be <a href="http://firepip.com/MuseSongRatings/results/">a follow up site to firepip's ranking site</a>, where users could vote on song match-ups, just like this one, and then see results in a big table along with other statistics. Then the goal is to use all the statistics on this site to guide any upcoming by-request gig votings (because go statistics, wow). Or to dispute a heated argument about which song is better than other..
                </p>

                <p>
                    Some stuff is still work in progress, but I wanted to get the voting out already so I can start gathering data now.
                </p>

                <p>
                    I plan to make more features, the stuff you currently see isn't final. Stuff I plan to do:
                </p>

                <ul>
                    <li>Win-rate and rank statistics from voting displayed on song pages and big table</li>
                    <li>Spotify play-counts (crowd-sourced data from users inputting it, since there's no API for it)</li>
                    <li><s>Spotify TOP10 index</s></li>
                    <li>YouTube views for songs</li>
                    <li>More statistic types on song page (need ideas)</li>
                    <li>Futher expand on set-list pages, e.g. display song staleness in set-list, last time song was played before x gig</li>
                    <li><s>Searching set-lists (allow searching based on notes e.g. outro rifs)</s></li>
                    <li>Allow users to pick setlists they have been to, and then generate some statistics based on that</li>
                    <li><s>View your own voting history and statistics for a voting ballot</s></li>
                </ul>

                <h2>FAQ</h2>

                <h5>Here's some Q&A that I think might be useful:</h5>

                <p><strong>How much do I have to vote?</strong></p>

                <p>It's up to you. The total we are looking for is what firepip had - 50,000 votes. You don't have to do it in one sitting, do it as a side-task e.g. when you are on commute and have nothing better to do, so you spend a few minutes on voting. If you need a goal, then 20% is good enough. Users who vote the most, will get their votes weighted more in the ranking!</p>

                <p><strong>This takes too long. Can we do the whole voting by drag and dropping tracks in order we like (from best to worst), instead of voting one by one?</strong></p>

                <p>
                    I have thought about this. It mostly comes down to implementation and dealing with concerns. I tried doing this sort of thing with SurveyMonkey for Shepherds Bush Empire by-request, but I heard complaints of it taking too much time. While doing manually all match-ups takes time too, I feel that the data will be more genuine. I am open to discussion about this.
                </p>

                <p><strong>Where is X song?</strong></p>

                <p>General rank voting-ballots may exclude some song tracks to reduce the match-up count. It's kind of difficult but here are rough rules for what gets included in main voting ballot rankings:</p>

                <ul>
                    <li>Studio album songs</li>
                    <li>Recorded and released tracks officially by the artist (must be purchasable or streamed legally)</li>
                    <li>The time since song release date must be minimum 45 days to avoid first impression bias</li>
                </ul>

                <p>Here's what doesn't make to the voting.</p>

                <ul>
                    <li>Remixes</li>
                    <li>Alternate versions</li>
                    <li>Tracks that are live versions, no studio available</li>
                    <li>Instrumental versions of songs</li>
                </ul>

                <p><strong>Why can't I see the statistics for the voting ballot now?</strong></p>

                <p>If I expose the statistics right now, then some users would be voting on match-ups based on how the song is currently doing in the ranking. You can however view your own voting history and statistics!</p>

                <p>However the site has ability to pre-calculate statistics whenever, meaning users can see the results, but the they would be frozen in time, outdated.</p>

                <p><strong>I have a problem / inquiry / feedback.</strong></p>

                <p>Please write me on Reddit /u/iamncla or e-mail: bythenumbers [at] ncla.me</p>

                {{--<p><strong></strong></p>--}}

                {{--<p></p>--}}
            </div>
        </div>

    </div>

@endsection