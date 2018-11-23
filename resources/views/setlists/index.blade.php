@extends('layouts.app')

@section('content')
    <div class="container mt-3 page-setlist-index">

        <div class="row">
            <div class="col-12">
                <h1 class="pull-left">Setlists</h1>
            </div>
        </div>

        <div class="clearfix"></div>

        @include('flash::message')

        <div class="search">
            {!! Form::open(['method' => 'GET', 'route' => 'setlists.index', 'role' => 'search', 'id' => 'setlist_search'])  !!}
                <div class="row">
                    <div class="col-12">
                        <h4>Search</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12 mb-1 mb-md-0">
                        {!! Form::text('venue', $searchValues['venue'] ?? null, ['class' => 'form-control', 'placeholder' => 'Venue']) !!}
                    </div>
                    <div class="col-md-4 col-12 mb-1 mb-md-0">
                        {!! Form::text('songs.name', $searchValues['songs.name'] ?? null, ['class' => 'form-control', 'placeholder' => 'Track name']) !!}
                    </div>
                    <div class="col-md-4 col-12 mb-1 mb-md-0">
                        {!! Form::text('songs.note', $searchValues['songs.note'] ?? null, ['class' => 'form-control', 'placeholder' => 'Track note']) !!}
                    </div>
                </div>

                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-sm mt-2 mb-2']); !!}

            {!! Form::close() !!}
        </div>

        @push('scripts')
            <script>
                $(function() {
                    $('#setlist_search').on('submit', function(e) {
                        e.preventDefault();

                        const form = document.querySelector('#setlist_search');
                        const data = new FormData(form);

                        let searchQuery = [];

                        Array.from(data).forEach(function(el) {
                            if(el[1].length > 0) {
                                searchQuery.push(el[0] + ':' + el[1]);
                            }
                        });

                        window.location.href = this.action + '?search=' + encodeURI(searchQuery.join(';')) + '&searchJoin=and';
                    });
                });
            </script>
        @endpush

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                @foreach($setlists as $setlist)
                        <div class="card mb-2">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/setlists/{!! $setlist->id !!}">
                                        {{ $setlist->venue['name'] }}, {{ $setlist->venue['city']['name'] }}, {{ $setlist->venue['city']['country']['name'] }}
                                    </a>
                                </h5>
                                <div class="card-text">{{ \Carbon\Carbon::parse($setlist->date)->toFormattedDateString() }}</div>
                                <div class="card-text setlist-tracks mb-1">
                                    @foreach($setlist->songs as $song)
                                        <span class="pr-1 font-weight-light">{{ $song->name }}</span>
                                    @endforeach
                                </div>
                                <div>
                                    <a href="/setlists/{!! $setlist->id !!}" class="btn btn-primary btn-sm mb-1">View</a>

                                    @can('manage-setlists')

                                        <a type="button" class="btn btn-danger btn-sm mb-1" href="{{ action('SetlistController@refresh', $setlist->id) }}">Refresh</a>

                                        @if($setlist->trashed())
                                            <a type="button" class="btn btn-danger btn-sm mb-1" href="{{ action('SetlistController@restore', $setlist->id) }}">Restore</a>
                                        @else
                                            <a type="button" class="btn btn-danger btn-sm mb-1" href="{{ action('SetlistController@delete', $setlist->id) }}">Delete</a>
                                        @endif

                                        <a type="button" class="btn btn-danger btn-sm mb-1" href="{{ action('SetlistController@utilize', [$setlist->id, 'set' => !$setlist->is_utilized]) }}">
                                            Mark As {{ $setlist->is_utilized == 1 ? 'Irrelevant' : 'Relevant' }}
                                        </a>

                                    @endcan
                                </div>

                            </div>
                        </div>
                @endforeach
            </div>
        </div>

        @include('core-templates::common.paginate', ['records' => $setlists])

    </div>
@endsection
