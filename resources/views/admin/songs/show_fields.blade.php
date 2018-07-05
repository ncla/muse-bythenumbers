<ul class="list-group mb-3">
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('id', 'Id:') !!}</h6>
            <small class="text-muted">{!! $songs->id !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('mbid', 'MBID:') !!}</h6>
            <small class="text-muted">{!! $songs->mbid !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('name', 'Name:') !!}</h6>
            <small class="text-muted">{!! $songs->name !!}</small>
            <div>
                <code>{!! $songs->name !!}</code>
            </div>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('name_override', 'Overriden name:') !!}</h6>
            <small class="text-muted">{!! $songs->name_override ? $songs->name_override : 'NULL' !!}</small>
            <div>
                <code>{!! $songs->name_override ? $songs->name_override : 'NULL' !!}</code>
            </div>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('name_spotify_override', 'Spotify overriden name:') !!}</h6>
            <small class="text-muted">{!! $songs->name_spotify_override ? $songs->name_spotify_override : 'NULL' !!}</small>
            <div>
                <code>{!! $songs->name_spotify_override ? $songs->name_spotify_override : 'NULL' !!}</code>
            </div>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('name_lastfm_override', 'LastFM overriden name:') !!}</h6>
            <small class="text-muted">{!! $songs->name_lastfm_override ? $songs->name_lastfm_override : 'NULL' !!}</small>
            <div>
                <code>{!! $songs->name_lastfm_override ? $songs->name_lastfm_override : 'NULL' !!}</code>
            </div>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('name_setlistfm_override', 'SetlistFM overriden name:') !!}</h6>
            <small class="text-muted">{!! $songs->name_setlistfm_override ? $songs->name_setlistfm_override : 'NULL' !!}</small>
            <div>
                <code>{!! $songs->name_setlistfm_override ? $songs->name_setlistfm_override : 'NULL' !!}</code>
            </div>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('manually_added', 'Is Manually Added:') !!}</h6>
            <small class="text-muted">{!! $songs->manually_added ? 'TRUE' : 'FALSE' !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('is_utilized', 'Is Utilized:') !!}</h6>
            <small class="text-muted">{!! $songs->is_utilized ? 'TRUE' : 'FALSE' !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('created_at', 'Created At:') !!}</h6>
            <small class="text-muted">{!! $songs->created_at !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('updated_at', 'Updated At:') !!}</h6>
            <small class="text-muted">{!! $songs->updated_at !!}</small>
        </div>
    </li>
</ul>

{{--<!-- Id Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('id', 'Id:') !!}--}}
    {{--<p>{!! $songs->id !!}</p>--}}
{{--</div>--}}

{{--<!-- Mbid Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('mbid', 'Mbid:') !!}--}}
    {{--<p>{!! $songs->mbid !!}</p>--}}
{{--</div>--}}

{{--<!-- Name Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('name', 'Name:') !!}--}}
    {{--<p>{!! $songs->name !!}</p>--}}
{{--</div>--}}

{{--<!-- Name Override Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('name_override', 'Name Override:') !!}--}}
    {{--<p>{!! $songs->name_override !!}</p>--}}
{{--</div>--}}

{{--<!-- Manually Added Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('manually_added', 'Manually Added:') !!}--}}
    {{--<p>{!! $songs->manually_added !!}</p>--}}
{{--</div>--}}

{{--<!-- Is Utilized Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('is_utilized', 'Is Utilized:') !!}--}}
    {{--<p>{!! $songs->is_utilized !!}</p>--}}
{{--</div>--}}

