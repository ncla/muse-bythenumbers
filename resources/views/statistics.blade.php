@extends('layouts.app')

@section('content')

    @push('scripts')
        <script>
            $(document).ready(function () {
                var t = $('#mainStatisticsTable').DataTable({
                    aLengthMenu: [
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ],
                    iDisplayLength: -1,
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0
                    }],
                    order: [[1, 'asc']]
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
            <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="mainStatisticsTable">
                <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    {{--<th scope="col">MBID</th>--}}
                    <th scope="col">LastFM Listeners</th>
                    <th scope="col">Last played</th>
                    <th scope="col">Total Live</th>
                    @foreach($years_columns as $year)
                        <th scope="col">{{ $year->year }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($works as $work)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td><a href="/songs/{{ $work->id }}">{{ $work->name_final }}</a></td>
                        {{--<td><a href="https://musicbrainz.org/work/{{ $work->mbid }}" target="_blank">MBID</a></td>--}}
                        <td>{{ $work->listeners_week > 0 ? $work->listeners_week : '' }}</td>
                        <td>{{ $work->last_played }}</td>
                        <td>{{ $work->playcount }}</td>
                        @foreach($years_columns as $year)
                            <td scope="col">{{ $work->{'total_' . $year->year} != 0 ? $work->{'total_' . $year->year} : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>

@endsection