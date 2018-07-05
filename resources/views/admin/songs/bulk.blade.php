@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-3">
        {!! Form::open(['url' => route('admin.songs.bulk.patch'), 'method' => 'patch']) !!}

        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <h1>Bulk editing songs</h1>
                </div>
                <div class="float-right">
                    {!! Form::submit(null, ['class' => 'btn btn-primary mr-1']) !!}
                    <a class="btn btn-outline-dark" href="{!! route('songs.index') !!}">Back</a>
                </div>
            </div>
        </div>


        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        {!! Form::select('action',
        [
        'is_utilized' => 'Set songs `is_utilized` to',
        'manually_added' => 'Set songs `manually_added` to',
        ], null, ['class' => 'custom-select custom-select-sm mb-2']) !!}

        {!! Form::select('boolean',
        [
        '0' => 'false',
        '1' => 'true',
        ], null, ['class' => 'custom-select custom-select-sm mb-2']) !!}

        <table class="table table-bordered table-sm table-xs table-hover" id="songs-table">
            <thead>
                <th></th>
                <th>Id</th>
                <th>Name</th>
                <th>Name Override</th>
                <th>Spotify Name</th>
                <th>LastFM Name</th>
                <th>SetlistFM Name</th>
                <th>Manually Added</th>
                <th>Is Utilized</th>
                <th colspan="3">Action</th>
            </thead>
            <tbody>
            @foreach($songs as $song)
                <tr>
                    <td>{!! Form::checkbox('selected[]', $song->id, null) !!}</td>
                    <td>{!! $song->id !!}</td>
                    <td>{!! $song->name !!}</td>
                    <td>{!! ($song->name_override !== null) ? $song->name_override : 'NULL' !!}</td>
                    <td>{!! ($song->name_spotify_override !== null) ? $song->name_spotify_override : 'NULL' !!}</td>
                    <td>{!! ($song->name_lastfm_override !== null) ? $song->name_lastfm_override : 'NULL' !!}</td>
                    <td>{!! ($song->name_setlistfm_override !== null) ? $song->name_setlistfm_override : 'NULL' !!}</td>
                    <td>{!! $song->manually_added ? 'TRUE' : 'FALSE' !!}</td>
                    <td>{!! $song->is_utilized ? 'TRUE' : 'FALSE' !!}</td>
                    <td>
                        <div class='btn-group'>
                            <a href="{!! route('songs.show', [$song->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>View</a>
                            <a href="{!! route('songs.edit', [$song->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>Edit</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{  Form::close() }}
    </div>

@endsection
