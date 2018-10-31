@extends('layouts.app')

@section('content')
    <div class="song-page">
        @if($albumImageUrl)
            <div class="container-fluid blur-head-bg p-0 mb-3">
                <div class="album-img">
                    <img src="{{ $albumImageUrl }}"/>
                </div>
                <div class="container header-text">
                    <div class="row h-100">
                        <div class="col-12 d-flex align-items-center">
                            <img src="{{ $albumImageUrl }}" class="img-fluid img-thumbnail album-artwork-img"/>
                            <h1 class="my-3 ml-3">{{ $song->name }}</h1>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                    $(window).on('load resize' ,function(e){
                        $('.album-img').height($('.header-text').outerHeight());
                    });
                </script>
            @endpush
        @else
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mt-3">{{ $song->name }}</h1>
                    </div>
                </div>
            </div>
        @endif

        <div class="container mt-1 pb-5">
            <div class="row">
                <div class="col-12">
                    <strong>Total performance count per year</strong>
                </div>

                @push('scripts')
                    <script src="//code.highcharts.com/stock/highstock.js"></script>
                @endpush

                @if(count($statsSetlistAppearance['years']) > 0)
                    <div class="col-12">
                        <div id="performanceCountChartcontainer" style=" height: 250px; margin: 0 auto" class="my-2"></div>
                    </div>

                    @push('scripts')
                        <script>

                            Highcharts.chart('performanceCountChartcontainer', {
                                chart: {
                                    type: 'column',
                                    marginTop: 25,
                                    zoomType: 'x'
                                },
                                title: {
                                    text: null
                                },
                                xAxis: {
                                    categories: {{ json_encode($statsSetlistAppearance['years']) }}
                                },
                                yAxis: {
                                    min: 0,
                                    title: {
                                        text: null
                                    },
                                    endOnTick: true
                                },
                                legend: {
                                    reversed: true
                                },
                                plotOptions: {
                                    column: {
                                        grouping: false
                                    },
                                    series: {
                                        maxPointWidth: 50
                                    }
                                },
                                tooltip: {
                                    shared: true
                                },
                                series: [{
                                    name: 'Total gigs',
                                    data: {{ json_encode($statsSetlistAppearance['totalgigs']) }},
                                    stack: 'year'
                                }, {
                                    name: 'Total performances',
                                    data: {{ json_encode($statsSetlistAppearance['plays']) }},
                                    stack: 'year'
                                }]
                            });
                        </script>
                    @endpush

                @else
                    <div class="col-12">
                        <div class="bg-white text-center p-3 my-2 border">
                            No data.
                        </div>
                    </div>
                @endif

            </div>

            <div class="row">
                <div class="col-12">
                    <strong>LastFM weekly listeners and chart position history</strong>
                </div>
            </div>

            @if(count($lastfmListenerHistory['listeners']) > 0)
                <div class="row">
                    <div class="col-12">
                        <div id="lastfmListenerscontainer" style=" height: 250px; margin: 0 auto" class="my-2"></div>
                    </div>
                </div>

                @push('scripts')
                    <script>
                        Highcharts.chart('lastfmListenerscontainer', {
                            chart: {
                                marginTop: 35,
                                zoomType: 'x'
                            },
                            navigator: {
                                enabled: true,
                                height: 30,
                                margin: 6
                            },
                            title: {
                                text: null
                            },
                            xAxis: {
                                type: 'datetime',
                                title: {
                                    text: null
                                },
                                crosshair: true,
                                range: 2 * 30 * 24 * 3600 * 1000
                            },
                            tooltip: {
                                shared: true
                            },
                            yAxis: [
                                {
                                    title: {
                                        text: 'Listeners (7 days)'
                                    }
                                },
                                {
                                    title: {
                                        text: 'Chart Index'
                                    },
                                    opposite: true,
                                    reversed: true,
                                    tickInterval: 1,
                                    allowDecimals: false,
                                }
                            ],
                            legend: {
                                layout: 'horizontal',
                            },
                            plotOptions: {
                                series: {
                                    label: {
                                        connectorAllowed: false
                                    },
                                    lineWidth: 1,
                                    animation: false,
                                    marker: {
                                        enabled: true,
                                        radius: 2,
                                        symbol: 'circle'
                                    },
                                    states: {
                                        hover: {
                                            enabled: false,
                                            lineWidthPlus: 2
                                        }
                                    }
                                }
                            },
                            series: [
                                {
                                    name: 'Listeners (7 days)',
                                    data: JSON.parse('{!! json_encode($lastfmListenerHistory['listeners']) !!}')
                                },
                                {
                                    name: 'Chart Index',
                                    data: JSON.parse('{!! json_encode($lastfmListenerHistory['chart_index']) !!}'),
                                    yAxis: 1
                                }
                            ],

                            responsive: {
                                rules: [{
                                    condition: {
                                        maxWidth: 500
                                    },
                                    chartOptions: {
                                        legend: {
                                            enabled: false
                                        }
                                    }
                                }]
                            }

                        });
                    </script>
                @endpush
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="bg-white text-center p-3 my-2 border">
                            No data.
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12 my-2">
                    <strong>Preceding and following entries in set-list
                        @if(count($setlistPrevNextTrack['prev']) > 0 || count($setlistPrevNextTrack['next']) > 0)
                            <button class="btn btn-sm btn-outline-secondary" id="toggle_lookaround">Toggle all entries</button>
                        @endif
                    </strong>
                </div>

                @push('scripts')
                    <script>
                        $('#toggle_lookaround').click(function () {
                            $(this).toggleClass('down');
                            $('#preceding_setlist_table, #following_setlist_table').toggleClass('table-lookaround-setlist-entries-only-five');
                        });
                    </script>
                @endpush

                <div class="col-12 col-md-6">
                    @if(count($setlistPrevNextTrack['prev']) > 0)
                        <table class="table table-bordered table-sm table-xs table-hover table-lookaround-setlist-entries-only-five" id="preceding_setlist_table">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($setlistPrevNextTrack['prev'] as $songName => $entries)
                                <tr @if(($loop->index + 1) > 5) class="more-than-five" @endif>
                                    <td @if($songName === '') class="font-italic" @endif>{{ $songName === '' ? 'None' : $songName }}</td>
                                    <td>{{ count($setlistPrevNextTrack['prev'][$songName]) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="bg-white text-center p-2 my-1 border">
                            No data.
                        </div>
                    @endif
                </div>
                <div class="col-12 col-md-6">
                    @if(count($setlistPrevNextTrack['next']) > 0)
                        <table class="table table-bordered table-sm table-xs table-hover table-lookaround-setlist-entries-only-five" id="following_setlist_table">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Count</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($setlistPrevNextTrack['next'] as $songName => $entries)
                                <tr @if(($loop->index + 1) > 5) class="more-than-five" @endif>
                                    <td @if($songName === '') class="font-italic" @endif>{{ $songName === '' ? 'None' : $songName }}</td>
                                    <td>{{ count($setlistPrevNextTrack['next'][$songName]) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="bg-white text-center p-2 my-1 border">
                            No data.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection
