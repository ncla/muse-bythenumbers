@extends('layouts.app')

@section('content')

    <div class="container-fluid mt-3">

        <div class="row">
            <div class="col-4">
                <p>All Musicbrainz works</p>
                <span>Total: {{ count($debug1) }}</span>

                <table class="table table-bordered table-sm table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($debug1 as $work)
                        <tr>
                            <th scope="row">{{ $work->id }}</th>
                            <td>{{ $work->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="col-4">
                <p>Musicbrainz works that couldn't be found in set-list songs (by name)</p>
                <span>Total: {{ count($debug2) }}</span>

                <table class="table table-bordered table-sm table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($debug2 as $work)
                        <tr>
                            <th scope="row">{{ $work->id }}</th>
                            <td>{{ $work->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="col-4">
                <p>Set-list songs that couldn't be found in Musicbrainz works (by name)</p>
                <span>Total: {{ count($debug3) }}</span>

                <table class="table table-bordered table-sm table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($debug3 as $work)
                        <tr>
                            <td>{{ $work->name }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection