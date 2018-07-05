<ul class="list-group mb-3">
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('id', 'Id:') !!}</h6>
            <small class="text-muted">{!! $user->id !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('username', 'Username:') !!}</h6>
            <small class="text-muted">{!! $user->username !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('avatar', 'Avatar:') !!}</h6>
            <small class="text-muted">{!! $user->avatar !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('remember_token', 'Remember Token:') !!}</h6>
            <small class="text-muted">{!! $user->remember_token !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('created_at', 'Created At:') !!}</h6>
            <small class="text-muted">{!! $user->created_at !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('updated_at', 'Updated At:') !!}</h6>
            <small class="text-muted">{!! $user->updated_at !!}</small>
        </div>
    </li>
</ul>


{{--<!-- Id Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('id', 'Id:') !!}--}}
    {{--<p>{!! $user->id !!}</p>--}}
{{--</div>--}}

{{--<!-- Username Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('username', 'Username:') !!}--}}
    {{--<p>{!! $user->username !!}</p>--}}
{{--</div>--}}

{{--<!-- Avatar Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('avatar', 'Avatar:') !!}--}}
    {{--<p>{!! $user->avatar !!}</p>--}}
{{--</div>--}}

{{--<!-- Remember Token Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('remember_token', 'Remember Token:') !!}--}}
    {{--<p>{!! $user->remember_token !!}</p>--}}
{{--</div>--}}

{{--<!-- Created At Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('created_at', 'Created At:') !!}--}}
    {{--<p>{!! $user->created_at !!}</p>--}}
{{--</div>--}}

{{--<!-- Updated At Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('updated_at', 'Updated At:') !!}--}}
    {{--<p>{!! $user->updated_at !!}</p>--}}
{{--</div>--}}

