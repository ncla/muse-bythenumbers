@extends('layouts.app')

@section('content')
        <div class="container mt-3">
                <h1 class="pull-left">Voting Ballots</h1>
                <a class="btn btn-primary pull-right" href="{!! route('votings.create') !!}">Add New</a>

                <div class="clearfix"></div>

                @include('flash::message')

                <div class="clearfix"></div>

                @include('admin.votings.table')
        </div>

@endsection
