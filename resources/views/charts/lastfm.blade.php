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
                            subtitle: {
                                text: 'Lower value is better'
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
                                    text: 'Chart index (7 days)'
                                },
                                min: 0,
                                max: 50,
                                reversed: true
                            },
                            tooltip: {
                                headerFormat: '<b>{series.name}</b><br>',
                                pointFormat: '{point.x:%Y-%m-%d}: <b>{point.y}</b>'
                            },

                            plotOptions: {
                                spline: {
                                    marker: {
                                        enabled: true
                                    }
                                },
                                series: {
                                    lineWidth: 1,
                                    animation: false,
                                    marker: {
                                        enabled: true,
                                        radius: 2
                                    }
                                }
                            },

                            zoomType: 'Y',
                            series: {!! $chart !!}
                        });
                    </script>
                @endpush

            </div>
        </div>
    </div>
@endsection
