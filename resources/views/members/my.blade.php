@php
    SEOMeta::setTitle('My Settings');
@endphp

@extends('layouts.app')

@section('content')
    <div class="container mt-3">

        {!! Form::open(['action' => 'SettingsController@update', 'method' => 'POST']) !!}

        <div class="row">
            <div class="col-12">
                <h1>My Settings</h1>
            </div>

            <div class="col-12 {{ $errors->has('settings.url') ? 'has-error' : '' }}">

                @include('flash::message')
                @include('core-templates::common.errors')

                <div class="row mb-2">

                    <div class="col-md-6 col-12">
                        {!! Form::label('settings[voting_embed]', 'Voting Page - Song Playback:') !!}
                        {!! Form::select('settings[voting_embed]', ['0' => 'No embed', '1' => 'Spotify embed', '2' => '30s sample audio embed'],
                        settings('voting_embed') ?? '1', ['class' => 'custom-select']); !!}
                    </div>

                    <div class="col-md-6 col-12">
                        {!! Form::label('settings[voting_progressbar]', 'Voting Page - Progress bar:') !!}
                        {!! Form::select('settings[voting_progressbar]', ['0' => 'Hide', '1' => 'Show'], settings('voting_progressbar') ?? '1', ['class' => 'custom-select']); !!}
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>

            </div>






        </div>

        {!! Form::close() !!}

    </div>

@endsection