@extends('layouts.app')

@section('content')
        <div class="container-fluid mt-3">

                <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h1>Songs</h1>
                            </div>
                            <div class="float-right">
                                <a class="btn btn-primary mr-1" href="{!! route('admin.songs.bulkmissing') !!}">Bulk Add Missing</a>
                                <a class="btn btn-primary mr-1" href="{!! route('admin.songs.bulk') !!}">Bulk Editing</a>
                                <a class="btn btn-primary" href="{!! route('songs.create') !!}">Add New</a>
                            </div>
                        </div>
                </div>

                <div class="clearfix"></div>

                @include('flash::message')

                <div class="clearfix"></div>

                @include('admin.songs.table')
        </div>

@endsection
