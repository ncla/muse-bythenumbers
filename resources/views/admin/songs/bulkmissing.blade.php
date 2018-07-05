@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-3">
        {!! Form::open(['url' => route('admin.songs.bulkmissing.patch'), 'method' => 'patch']) !!}

        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <h1>Bulk adding missing songs</h1>
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

        <table class="table table-bordered table-sm table-xs table-hover" id="songs-table">
            <thead>
                <th></th>
                <th>Name</th>
            </thead>
            <tbody>
            @foreach($songs as $song)
                <tr>
                    <td>{!! Form::checkbox('selected[]', $song, null) !!}</td>
                    <td>{!! $song !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{  Form::close() }}
    </div>

@endsection
