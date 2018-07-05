@extends('layouts.app')

@section('content')

    <div class="container mt-3">

        <div class="row">
            <div class="col-12">
                <h1>About</h1>

                <p>
                    ByTheNumbers is made by ncla (/u/iamncla) and is suppose to be <a href="http://firepip.com/MuseSongRatings/results/">a follow up site to firepip's ranking site</a>, where users could vote on song match-ups, just like this one, and then see results in a big table along with other statistics. Then the goal is to use all the statistics on this site to guide any upcoming by-request gig votings (because go statistics, wow).
                </p>

                <p>
                    Some stuff is still work in progress, but I wanted to get the voting out already so I can start gathering data now.
                </p>

                <p>
                    I plan to make more features, the stuff you currently see isn't final. Stuff I plan to do:
                </p>

                <ul>
                    <li>Win-rate and rank statistics from voting displayed on song pages and big table</li>
                    <li>Spotify play-counts</li>
                    <li>Spotify TOP10 index</li>
                    <li>YouTube views for songs</li>
                    <li>Improve voting loading speed</li>
                    <li>More statistic types on song page (need ideas)</li>
                    <li>Futher expand on set-list pages, e.g. display song staleness in set-list, last time song was played before x gig</li>
                    <li>Searching set-lists (allow searching based on notes e.g. outro rifs)</li>
                    <li>Allow users to pick setlists they have been to, and then generate some statistics based on that</li>
                </ul>

                <h2>FAQ</h2>

                <h5>Here's some Q&A that I think might be useful:</h5>

                <p><strong>How much do I have to vote?</strong></p>

                <p>It's up to you. The total we are looking for is what firepip had - 50,000 votes. You don't have to do it in one sitting, do it as a side-task e.g. when you are on commute and have nothing better to do, so you spend a few minutes on voting.</p>

                <p><strong>How many match-ups are there in total?</strong></p>

                <p>I prefer not to reveal this as it might be too overwhelming/discouraging for some, but you can do the math yourself.</p>

                <p><strong>Where is X song?</strong></p>

                <p>General rank voting-ballots may exclude some song tracks to reduce the match-up count. These would be live tracks, live (non-studio) covers, variations of same song, demos, short tracks that are intros/interludes etc., or stuff you can't find officially.</p>

                <p><strong>I have a problem / enquiry / feedback.</strong></p>

                <p>/u/iamncla on Reddit or e-mail: bythenumbers [at] ncla.me</p>
            </div>
        </div>

    </div>

@endsection