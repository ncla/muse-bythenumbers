@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row setlist-page-header" style="background-image: url('https://maps.googleapis.com/maps/api/staticmap?center={{ $setlist->venue['city']['coords']['lat'] }},{{ $setlist->venue['city']['coords']['long'] }}&zoom=12&size=640x640&scale=2&key=AIzaSyAKEZH9JcoFJXcj4JDYAzTo8BcYvs-jCrE&maptype=roadmap&style=element:geometry%7Ccolor:0x242f3e&style=element:labels.text.fill%7Ccolor:0x746855&style=element:labels.text.stroke%7Ccolor:0x242f3e&style=feature:administrative.locality%7Celement:labels.text.fill%7Ccolor:0xd59563&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0xd59563&style=feature:poi.park%7Celement:geometry%7Ccolor:0x263c3f&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x6b9a76&style=feature:road%7Celement:geometry%7Ccolor:0x38414e&style=feature:road%7Celement:geometry.stroke%7Ccolor:0x212a37&style=feature:road%7Celement:labels.text.fill%7Ccolor:0x9ca5b3&style=feature:road.highway%7Celement:geometry%7Ccolor:0x746855&style=feature:road.highway%7Celement:geometry.stroke%7Ccolor:0x1f2835&style=feature:road.highway%7Celement:labels.text.fill%7Ccolor:0xf3d19c&style=feature:transit%7Celement:geometry%7Ccolor:0x2f3948&style=feature:transit.station%7Celement:labels.text.fill%7Ccolor:0xd59563&style=feature:water%7Celement:geometry%7Ccolor:0x17263c&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x515c6d&style=feature:water%7Celement:labels.text.stroke%7Ccolor:0x17263c')">

            <div class="container d-flex h-100">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h1><span class="fancy-text-bg">Setlist</span></h1>
                        <h5>
                            <span class="fancy-text-bg">{{ $setlist->venue['name'] }}, {{ $setlist->venue['city']['name'] }}, {{ $setlist->venue['city']['country']['name'] }}</span>
                        </h5>
                        <p><span class="fancy-text-bg">{{ \Carbon\Carbon::parse($setlist->date)->toFormattedDateString() }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-3 pb-2">
                <div class="row">
                    <div class="col-12 mb-2">
                        <ul class="list-group setlist">
                            @php
                                $i = 0;
                            @endphp

                    @foreach($setlist->songs()->get() as $setlistSong)
                        @php
                        if($setlistSong->tape !== 1) {
                            $i++;
                        }
                        @endphp
                        <li class="list-group-item">
                            @if(!$loop->first && $setlistSong->order_nr_in_set === 0)
                                <small class="font-weight-bold">Encore {{ $setlistSong->encore }}</small>
                            @endif
                            <p class="m-0 font-weight-bold">@if($setlistSong->tape !== 1){{ $i }}. @else 🖭 @endif {{ $setlistSong->name }}</p>
                            @if($setlistSong->note)
                                <small>{{ $setlistSong->note }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-12">
                <a href="{!! route('setlists.index') !!}" class="btn btn-default btn-primary btn-sm">Back</a>
            </div>
        </div>

    </div>
@endsection
