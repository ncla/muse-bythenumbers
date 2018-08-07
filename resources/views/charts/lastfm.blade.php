@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-3 pb-5">
        <div class="row">
            <div class="col-12">
                <h1>LastFM Chart History</h1>
            </div>

            <div class="col-12">

                <div id="container" style="min-width: 310px; height: 1400px; margin: 0 auto"></div>

                @push('scripts')
                    <script src="https://code.highcharts.com/highcharts.js"></script>

                    <script>

                        Highcharts.chart('container', {
                            chart: {
                                type: 'spline'
                            },
                            title: {
                                text: 'Last.FM Chart Index'
                            },
                            xAxis: {
                                type: 'datetime',
                                dateTimeLabelFormats: { // don't display the dummy year
                                    month: '%e. %b',
                                    year: '%b'
                                },
                                title: {
                                    text: 'Date'
                                }
                            },
                            yAxis: {
                                title: {
                                    text: 'Listeners (7 days)'
                                },
                                min: 0,
                                reversed: true
                            },
                            tooltip: {
                                headerFormat: '<b>{series.name}</b><br>',
                                pointFormat: '{point.x:%e. %b}: {point.y:.2f}'
                            },

                            plotOptions: {
                                spline: {
                                    marker: {
                                        enabled: true
                                    }
                                }
                            },

                            colors: ['#6CF', '#39F', '#06C', '#036', '#000'],

                            // Define the data points. All series have a dummy year
                            // of 1970/71 in order to be compared on the same x axis. Note
                            // that in JavaScript, months start at 0 for January, 1 for February etc.
                            series: {!! $chart !!}
                        });
                    </script>
                @endpush

            </div>
        </div>
    </div>
@endsection
