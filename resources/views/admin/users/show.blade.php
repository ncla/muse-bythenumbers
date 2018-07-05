@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="">Viewing User</h1>

        @include('admin.users.show_fields')

        <div class="form-group">
               <a href="{!! route('users.index') !!}" class="btn btn-default btn-primary">Back</a>
        </div>
    </div>
@endsection
