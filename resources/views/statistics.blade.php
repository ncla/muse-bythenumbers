@extends('layouts.app')

@section('content')

    @push('scripts')
        <script>
            $(document).ready(function () {
                // https://stackoverflow.com/questions/25944854/sorting-alphabetical-order-to-ignore-empty-cells-datatables
                // https://stackoverflow.com/questions/16318092/jquery-datatables-sorting-neglect-null-value
                $.fn.dataTableExt.oSort['nullable-asc'] = function (a, b) {
                    if (a === '')
                        return 1;
                    else if (b === '')
                        return -1;
                    else {
                        var ia = parseInt(a);
                        var ib = parseInt(b);
                        return (ia < ib) ? -1 : ((ia > ib) ? 1 : 0);
                    }
                };

                $.fn.dataTableExt.oSort['nullable-desc'] = function (a, b) {
                    if (a === '')
                        return 1;
                    else if (b === '')
                        return -1;
                    else {
                        var ia = parseInt(a);
                        var ib = parseInt(b);
                        return (ia > ib) ? -1 : ((ia < ib) ? 1 : 0);
                    }
                };

                var t = $('#mainStatisticsTable').DataTable({
                    aLengthMenu: [
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ],
                    iDisplayLength: 25,
                    columnDefs: [
                        {
                            searchable: false,
                            orderable: false,
                            targets: 0
                        },
                        {
                            type: 'nullable',
                            targets: 'nullable-sort-col'
                        }
                    ],
                    order: [
                        [$('th.lastfm-col').index(), 'desc']
                    ]
                });

                // https://stackoverflow.com/questions/33432115/jquery-datatables-static-rownumber
                t.on('order.dt search.dt', function () {
                    t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

            });
        </script>
    @endpush

    <div class="container-fluid mt-3 pb-3">
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover table-xs dt-responsive table-striped" id="mainStatisticsTable">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Track Name</th>
                    @if($votingStatsExist)
                        <th scope="col" data-toggle="tooltip" title="Calculated from voting ballot data on {{ $preCalculatedStatsDate }}">ELO rank</th>
                        <th scope="col" data-toggle="tooltip" title="Calculated from voting ballot data on {{ $preCalculatedStatsDate }}">Winrate</th>
                    @endif
                    <th scope="col" data-toggle="tooltip" title="LastFM Last 7 Days Listeners" class="lastfm-col"><span class="table-lastfm-icon"></span> 7day</th>
                    <th scope="col" data-toggle="tooltip" title="Spotify TOP10 Chart Index" class="nullable-sort-col"><span class="table-spotify-icon"></span> Top10</th>
                    <th scope="col" data-toggle="tooltip" title="Based on SetlistFM data">Last played</th>
                    <th scope="col" data-toggle="tooltip" title="Based on SetlistFM data">Total Live</th>
                    @foreach($years_columns as $year)
                        <th scope="col" data-toggle="tooltip" title="Based on SetlistFM data">{{ $year->year }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($works as $work)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td><a href="/songs/{{ $work->id }}/{{ rawurlencode($work->name_final) }}">{{ $work->name_final }}</a></td>
                        @if($votingStatsExist)
                            <td>{{ $work->elo_rank ? number_format($work->elo_rank, 2, '.', '') : null }}</td>
                            <td
                                    @if($work->winrate)
                                    data-toggle="tooltip" data-html="true" title="{{ $work->votes_won . '/' . $work->total_votes }}"
                                    @endif
                            >
                                {{ $work->winrate ? number_format($work->winrate, 2) . '%' : null }}
                            </td>
                        @endif
                        <td>{{ $work->listeners_week > 0 ? $work->listeners_week : '' }}</td>
                        <td>{{ $work->chart_index }}</td>
                        <td>{{ $work->last_played }}</td>
                        <td>{{ $work->playcount }}</td>
                        @foreach($years_columns as $year)
                            @php
                                $totalPerf = $work->{'total_' . $year->year};
                                $totalGigs = $years_total_gigs[$year->year]->total_gigs;
                                $percentageOfGigs = (($totalGigs === 0 || $totalPerf === 0) ? 0 : ($totalPerf / $totalGigs) * 100);
                            @endphp
                            <td data-toggle="tooltip" data-html="true"
                                title="{{ $totalPerf }}/{{ $totalGigs }} ({{ round($percentageOfGigs, 2) }}%)">
                                {{ $totalPerf != 0 ? $work->{'total_' . $year->year} : '' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $('body').tooltip({selector: '[data-toggle="tooltip"]'});
        </script>
    @endpush
@endsection