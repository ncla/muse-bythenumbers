@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Edit Voting Ballot</h1>
            </div>
        </div>

        @include('core-templates::common.errors')

        <div class="row">
            <div class="col-12">

                <div class="alert alert-danger" role="alert">
                    Caution! If you are deleting and/or adding new songs to the list, loss of data such as voting matchups and votes by users is possible! If you are only adding songs, you should be fine.
                </div>

                {!! Form::model($voting, ['route' => ['votings.update', $voting->id], 'method' => 'patch']) !!}

                @include('admin.votings.fields')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
