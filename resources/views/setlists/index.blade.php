@extends('layouts.app')

@section('content')
    <div class="container mt-3">

        <div class="row">
            <div class="col-12">
                <h1 class="pull-left">Setlists</h1>
            </div>
        </div>
        {{--<a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('setlists.create') !!}">Add New</a>--}}

        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-12">
                @foreach($setlists as $setlist)
                        {{--{{ json_encode($setlist->venue) }} }}--}}
                        <div class="card mb-2">
                            {{--<img class="card-img-top" src="..." alt="Card image cap">--}}
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/setlists/{!! $setlist->id !!}">
                                        {{ $setlist->venue['name'] }}, {{ $setlist->venue['city']['name'] }}, {{ $setlist->venue['city']['country']['name'] }}
                                    </a>
                                </h5>
                                <p class="card-text">{{ \Carbon\Carbon::parse($setlist->date)->toFormattedDateString() }}</p>
                                <a href="/setlists/{!! $setlist->id !!}" class="btn btn-primary btn-sm">View</a>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>

        @include('core-templates::common.paginate', ['records' => $setlists])

    </div>
@endsection
