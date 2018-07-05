@extends('layouts.app')

@section('content')
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Edit Setlist</h1>
            </div>
        </div>

        @include('core-templates::common.errors')

        <div class="row">
            {!! Form::model($setlist, ['route' => ['setlists.update', $setlist->id], 'method' => 'patch']) !!}

            @include('setlists.fields')

            {!! Form::close() !!}
        </div>
@endsection
