<ul class="list-group mb-3">
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('id', 'Id:') !!}</h6>
            <small class="text-muted">{!! $voting->id !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('name', 'Name:') !!}</h6>
            <small class="text-muted">{!! $voting->name !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('description', 'Description:') !!}</h6>
            <small class="text-muted">{!! $voting->description !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('songs', 'Song list:') !!}</h6>
            <div>
                <code>
                    {{ $songs->implode('name', ', ') }}
                </code>
            </div>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <style>
            [v-cloak] {
                display: none;
            }
        </style>

        <div id="matchups-list">
            <h6 class="my-0">{!! Form::label('matchups', 'Song matchups:') !!}</h6>
            <div>
                <button type="button" class="btn btn-outline-secondary btn-sm" v-on:click="toggle">Show all {{ count($matchups) }} match-ups</button>
            </div>
            <div v-show="isShown" v-cloak>
                    @foreach($matchups as $matchup)
                    <div>
                        <small><b>{{ $matchup->songA_name }}</b> vs <b>{{ $matchup->songB_name }}</b></small>
                    </div>
                    @endforeach
            </div>

        </div>
        @push('scripts')
            <script src="{{ mix('js/admin/voting-ballot.js') }}"></script>
            <script>
                new Vue({
                    el: '#matchups-list',
                    data: {
                        isShown: false
                    },
                    methods: {
                        toggle: function () {
                            this.isShown = !this.isShown;
                        }
                    }
                })
            </script>
        @endpush
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('is_open', 'Is Open:') !!}</h6>
            <small class="text-muted">{!! $voting->is_open !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('matchup_serve_method', 'Matchup Serve Method:') !!}</h6>
            <small class="text-muted">{!! $voting->matchup_serve_method !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('expires_on', 'Expires On:') !!}</h6>
            <small class="text-muted">{!! $voting->expires_on !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('type', 'Type:') !!}</h6>
            <small class="text-muted">{!! $voting->type !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('created_at', 'Created At:') !!}</h6>
            <small class="text-muted">{!! $voting->created_at !!}</small>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <div>
            <h6 class="my-0">{!! Form::label('updated_at', 'Updated At:') !!}</h6>
            <small class="text-muted">{!! $voting->updated_at !!}</small>
        </div>
    </li>
</ul>

{{--<!-- Id Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('id', 'Id:') !!}--}}
    {{--<p>{!! $voting->id !!}</p>--}}
{{--</div>--}}

{{--<!-- Name Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('name', 'Name:') !!}--}}
    {{--<p>{!! $voting->name !!}</p>--}}
{{--</div>--}}

{{--<!-- Description Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('description', 'Description:') !!}--}}
    {{--<p>{!! $voting->description !!}</p>--}}
{{--</div>--}}

{{--<!-- Songs Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('songs', 'Songs:') !!}--}}
    {{--<p>{!! $voting->songs !!}</p>--}}
{{--</div>--}}

{{--<!-- Is Open Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('is_open', 'Is Open:') !!}--}}
    {{--<p>{!! $voting->is_open !!}</p>--}}
{{--</div>--}}

{{--<!-- Expires On Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('expires_on', 'Expires On:') !!}--}}
    {{--<p>{!! $voting->expires_on !!}</p>--}}
{{--</div>--}}

{{--<!-- Type Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('type', 'Type:') !!}--}}
    {{--<p>{!! $voting->type !!}</p>--}}
{{--</div>--}}

{{--<!-- Created At Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('created_at', 'Created At:') !!}--}}
    {{--<p>{!! $voting->created_at !!}</p>--}}
{{--</div>--}}

{{--<!-- Updated At Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('updated_at', 'Updated At:') !!}--}}
    {{--<p>{!! $voting->updated_at !!}</p>--}}
{{--</div>--}}

