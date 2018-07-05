@extends('layouts.app')

@section('content')
    <div class="container mt-3 pb-5">
        <div class="row">
            <div class="col-12">
                <h1>{{ $song->name }}</h1>
                <strong>Total performance count per year</strong>
            </div>
            <div style="height: 250px" class="col-12">
                <canvas id="performanceCountChart" height="200px"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-12 my-2">
                <strong>Preceding and following entries in set-list <button class="btn btn-sm btn-outline-secondary" id="toggle_lookaround">Toggle all entries</button></strong>
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
            </div>
            <div class="col-12 col-md-6">
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
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <strong>LastFM weekly listeners and chart position history</strong>
            </div>
            <div style="height: 250px" class="col-12">
                <canvas id="lastfmListeners" height="200px"></canvas>
            </div>
        </div>

        @push('scripts')
            <script>
                var performanceCountChart = new Chart(document.getElementById("performanceCountChart").getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {{ json_encode($statsSetlistAppearance['years']) }},
                        datasets: [
                            {
                                label: '# of performances',
                                data: {{ json_encode($statsSetlistAppearance['plays']) }},
                                backgroundColor: 'rgb(95, 167, 206)',
                                borderWidth: 0
                            },
                            {
                                label: '# of total gigs',
                                data: {{ json_encode($statsSetlistAppearance['totalgigs']) }},
                                backgroundColor: 'rgb(164, 201, 221)',
                                borderWidth: 0
                            }
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                }
                            }],
                            xAxes: [{
                                stacked: true,
                                maxBarThickness: 100,
                                padding: {
                                    top: 50,
                                },
                            }]
                        },
                        tooltips: {
                            yAlign: 'bottom'
                        },
                        layout: {
                            padding: {
                                top: 10,
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });

                var lastFmChart = new Chart(document.getElementById("lastfmListeners").getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: JSON.parse('{!! json_encode($lastfmListenerHistory['time']) !!}'),
                        datasets: [
                            {
                                label: '# of listeners in last 7 days',
                                data: JSON.parse('{!! json_encode($lastfmListenerHistory['listeners']) !!}'),
                                // lineTension: 0,
                                backgroundColor: 'rgba(0, 0, 0, 0)',
                                borderColor: '#E34A58',
                                yAxisID: 'y-axis-listeners'
                            },
                            {
                                label: 'Chart position',
                                data: JSON.parse('{!! json_encode($lastfmListenerHistory['chart_index']) !!}'),
                                lineTension: 0,
                                backgroundColor: 'rgba(0, 0, 0, 0)',
                                borderColor: '#33BEED',
                                yAxisID: 'y-axis-chartposition',
                            }
                        ]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    displayFormats: {'day': 'DD-MM'},
                                    tooltipFormat: 'DD/MM/YY',
                                    unit: 'day',
                                },
                                padding: {
                                    top: 50,
                                },
                            }],
                            yAxes: [
                                {
                                    id: 'y-axis-listeners',
                                    position: 'left',
                                },
                                {
                                    id: 'y-axis-chartposition',
                                    position: 'right',
                                    // ticks: {
                                    //     stepSize: 1
                                    // }
                                }
                            ]
                        },
                        layout: {
                            padding: {
                                top: 10,
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            </script>
        @endpush

    </div>
@endsection
