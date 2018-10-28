<!-- Name Field -->
<div class="mb-3">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="mb-3">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
</div>

<div class="mb-3">

    <h4 class="">Songs</h4>

    <div id="song-list">

        <button type="button" v-bind:disabled="songsAvailable.length == 0" class="btn btn-secondary btn-sm mr-1 mb-1" v-on:click="addAllAvailable">Add all available songs</button>
        <button type="button" v-bind:disabled="songsSelected.length == 0" class="btn btn-secondary btn-sm mr-1 mb-1" v-on:click="removeAllSelected">Remove all selected songs</button>

        <div>
            {!! Form::label('songs', 'Selected:', ['class' => 'font-weight-bold']) !!}

            <span v-if="songsSelected.length == 0">None</span>

            <span v-for="(song, index) in songsSelected">
                <button type="button" class="btn btn-outline-success btn-sm mr-1 mb-1" v-on:click="removeFromSongList(song, index)">@{{ song.name }}</button>
                <input type="hidden" name="songs[][song_id]" v-bind:value="song.id" />
            </span>
        </div>

        <div>
            {!! Form::label('songs', 'Available:', ['class' => 'font-weight-bold']) !!}

            <span v-if="songsAvailable.length == 0">None</span>

            <span v-for="(song, index) in orderedSongsAvailable" :key="song.id">
                <button type="button" class="btn btn-outline-secondary btn-sm mr-1 mb-1" v-on:click="addToSongList(song, index)">@{{ song.name }}</button>
            </span>
        </div>

        <hr/>

    </div>

    @push('scripts')
        <script>
            var songsAvailable = {!! $songsAvailable !!};
            var songsSelected = {!! $songsSelected !!};
        </script>
        <script src="{{ mix('js/admin/voting-ballot.js') }}"></script>
    @endpush
</div>

<script>

</script>

<!-- Is Open Field -->
<div class="mb-3">
    {!! Form::label('is_open', 'Is Open:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('is_open', 0) !!}
        {!! Form::checkbox('is_open', (isset($model) ? null : 1)) !!}
    </label>
</div>

<div class="mb-3">
    {!! Form::label('matchup_serve_method', 'Matchup Serve Method ID:') !!}
    {!! Form::text('matchup_serve_method', null, ['class' => 'form-control']) !!}
</div>

<!-- Expires On Field -->
<div class="mb-3">
    {!! Form::label('expires_on', 'Expires On:') !!}
    {!! Form::datetime('expires_on', isset($voting) ? null : \Carbon\Carbon::now()->addWeeks(2)->toDateTimeString(), ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="mb-3">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::select('type', ['ranking' => 'Ranking', 'other' => 'Other'], isset($voting) ? null : 'ranking', ['class' => 'custom-select']) !!}
</div>

<!-- Submit Field -->
<div class="mb-3">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('votings.index') !!}" class="btn btn-default">Cancel</a>
</div>
