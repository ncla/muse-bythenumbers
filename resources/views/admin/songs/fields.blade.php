<!-- Mbid Field -->
<div class="mb-3">
    {!! Form::label('mbid', 'Mbid:') !!}
    {!! Form::text('mbid', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="mb-3">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Override Field -->
<div class="mb-3">
    {!! Form::label('name_override', 'Name Override:') !!}
    {!! Form::text('name_override', null, ['class' => 'form-control']) !!}
</div>

<!-- Spotify Name Override Field -->
<div class="mb-3">
    {!! Form::label('name_spotify_override', 'Spotify Name Override:') !!}
    {!! Form::text('name_spotify_override', null, ['class' => 'form-control']) !!}
</div>

<!-- LastFM Name Override Field -->
<div class="mb-3">
    {!! Form::label('name_lastfm_override', 'LastFM Name Override:') !!}
    {!! Form::text('name_lastfm_override', null, ['class' => 'form-control']) !!}
</div>

<!-- SetlistFM Name Override Field -->
<div class="mb-3">
    {!! Form::label('name_setlistfm_override', 'SetlistFM Name Override:') !!}
    {!! Form::text('name_setlistfm_override', null, ['class' => 'form-control']) !!}
</div>

<!-- Manually Added Field -->
<div class="mb-3">
    {!! Form::label('manually_added', 'Manually Added:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('manually_added', false) !!}
        {!! Form::checkbox('manually_added', true) !!}
    </label>
</div>

<!-- Is Utilized Field -->
<div class="mb-3">
    {!! Form::label('is_utilized', 'Is Utilized:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('is_utilized', false) !!}
        {!! Form::checkbox('is_utilized', true) !!}
    </label>
</div>

<!-- Submit Field -->
<div class="mb-3">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('songs.index') !!}" class="btn btn-default">Cancel</a>
</div>
