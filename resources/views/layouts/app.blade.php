<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    {!! SEO::generate() !!}

    @include('layouts.tracking')
</head>
<body>

<wrapper class="d-flex flex-column">

<nav class="navbar navbar-expand-md border-bottom box-shadow navbar-light bg-white">
    <a class="navbar-brand" href="/">DEEPCUTS Live</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">

            <ul class="navbar-nav mr-md-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Statistics</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link mr-0" href="#" id="navbarDropdownBrowse" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Browse
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownBrowse">
                        <a class="dropdown-item" href="{{ action('VotingController@index') }}">Votings</a>
                        <a class="dropdown-item" href="{{ action('SetlistController@index') }}">Setlists</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="dropdown-toggle nav-link mr-0" href="#" id="navbarDropdownChartHistory" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Chart History
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownChartHistory">
                        <a class="dropdown-item" href="{{ action('ChartHistoryController@showLastFm') }}">LastFM</a>
                        <a class="dropdown-item" href="{{ action('ChartHistoryController@showSpotifyTop10') }}">Spotify</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/about">About</a>
                </li>
            </ul>

            @auth
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="navbar-brand dropdown-toggle nav-link mr-0" href="#" id="navbarDropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>
                        {{ Auth::user()->username }}
                        </span>
                            <img src="{{ Auth::user()->avatar }}" class="rounded" width="30" height="30"/>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            {{--<a class="dropdown-item" href="#">My Profile</a>--}}

                            {{--<div class="dropdown-divider"></div>--}}
                            @if (Auth::user()->isAn('admin') || Auth::user()->isAn('superadmin'))
                                <h6 class="dropdown-header font-weight-bold">Admin</h6>

                                @if (Auth::user()->isAn('superadmin'))
                                    <a class="dropdown-item" href="{{config('nova.path') }}">Nova</a>
                                @endif

                                @can('manage-voting-ballots')
                                    <a class="dropdown-item" href="{{ route('votings.index') }}">Manage Voting Ballots</a>
                                @endcan

                                @can('manage-users')
                                    <a class="dropdown-item" href="{{ route('users.index') }}">Manage Users</a>
                                @endcan

                                @can('manage-songs')
                                    <a class="dropdown-item" href="{{ route('songs.index') }}">Manage Songs</a>
                                @endcan

                                <div class="dropdown-divider"></div>
                            @endif

                            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        </div>
                    </li>
                </ul>
            @endauth


        @guest
            <a class="btn btn-primary btn-light" href="{{ route('login') }}">Login with Reddit</a>
        @endguest
    </div>
</nav>

<main class="flex-fill">

@yield('content')

</main>

<footer class="footer bg-white border-top">
    <div class="container text-muted">
        <div class="row">
            <div class="col-sm-6">
                <div>Developed by <a href="https://ncla.me">ncla</a>, © {{ date('Y') }}</div>
                <div>Data provided by Setlist.fm, Last.fm, Spotify</div>
            </div>
            <div class="col-sm-6 text-sm-right">
                @php
                    if (Cache::get('ver')) {
                        $ver = cache('ver');
                    } else {
                        $ver = App\Src\ApplicationVersion::get();
                        Cache::put('ver', $ver, 10);
                    }
                @endphp
                <div>Application version: {{ $ver }}</div>
            </div>
        </div>
    </div>
</footer>

</wrapper>

<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts')



</body>
</html>