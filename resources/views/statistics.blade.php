@extends('layouts.app')

@section('content')

    {{--<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>--}}
    {{--<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css"/>--}}
    {{--<link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css"/>--}}
    {{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>--}}


    @push('scripts')
        {{--<script type="text/javascript" src="{{ mix('js/datatable.js') }}"></script>--}}
        <script>
            $(document).ready(function () {
                $('#mainStatisticsTable').DataTable({
                    aLengthMenu: [
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ],
                    iDisplayLength: -1
                });
            });
        </script>
    @endpush

    <div class="container-fluid mt-3 pb-3">
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover table-xs dt-responsive" id="mainStatisticsTable">
                <thead class="thead-light">
                <tr>
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