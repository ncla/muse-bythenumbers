@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Create New Songs</h1>
            </div>
        </div>

        @include('core-templates::common.errors')

        <div class="row">
            <div class="col-12">
                {!! Form::open(['route' => 'songs.store']) !!}

                    @include('admin.songs.fields')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
