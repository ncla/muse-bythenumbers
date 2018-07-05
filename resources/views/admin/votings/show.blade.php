@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="">Viewing Voting Ballot</h1>

        @include('admin.votings.show_fields')

        <div class="form-group">
            <a href="{!! route('votings.index') !!}" class="btn btn-default btn-primary">Back</a>
        </div>
    </div>
@endsection
