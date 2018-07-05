<table class="table table-bordered table-sm table-xs table-hover" id="songs-table">
    <thead>
        <th>Mbid</th>
        <th>Name</th>
        <th>Name Override</th>
        <th>Spotify Name</th>
        <th>LastFM Name</th>
        <th>SetlistFM Name</th>
        <th>Manually Added</th>
        <th>Is Utilized</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($songs as $song)
        <tr>
            <td>{!! $song->mbid !!}</td>
            <td>{!! $song->name !!}</td>
            <td>{!! ($song->name_override !== null) ? $song->name_override : 'NULL' !!}</td>
            <td>{!! ($song->name_spotify_override !== null) ? $song->name_spotify_override : 'NULL' !!}</td>
            <td>{!! ($song->name_lastfm_override !== null) ? $song->name_lastfm_override : 'NULL' !!}</td>
            <td>{!! ($song->name_setlistfm_override !== null) ? $song->name_setlistfm_override : 'NULL' !!}</td>
            <td>{!! $song->manually_added ? 'TRUE' : 'FALSE' !!}</td>
            <td>{!! $song->is_utilized ? 'TRUE' : 'FALSE' !!}</td>
            <td>
                {!! Form::open(['route' => ['songs.destroy', $song->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('songs.show', [$song->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>View</a>
                    <a href="{!! route('songs.edit', [$song->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>Edit</a>
                    {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
