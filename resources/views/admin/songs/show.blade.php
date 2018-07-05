@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="">Viewing Song</h1>

        @include('admin.songs.show_fields')

        <div class="form-group">
            <a href="{!! route('songs.edit', ['id' => $songs->id]) !!}" class="btn btn-default btn-primary">Edit</a>
            <a href="{!! route('songs.index') !!}" class="btn btn-default btn-primary">Back</a>
        </div>
    </div>
@endsection
