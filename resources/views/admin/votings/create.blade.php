@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Create New Voting Ballot</h1>
            </div>
        </div>

        @include('core-templates::common.errors')

        <div class="row">
            <div class="col-12">
                {!! Form::open(['route' => 'votings.store']) !!}

                @include('admin.votings.fields')

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
