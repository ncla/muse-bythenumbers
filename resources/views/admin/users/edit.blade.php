@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Edit User</h1>
            </div>
        </div>

        @include('core-templates::common.errors')

        <div class="row">
            <div class="col-12">
            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}

            @include('admin.users.fields')

            {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
