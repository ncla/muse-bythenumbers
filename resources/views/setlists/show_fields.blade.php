<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $setlist->id !!}</p>
</div>

<!-- Date Field -->
<div class="form-group">
    {!! Form::label('date', 'Date:') !!}
    <p>{!! $setlist->date !!}</p>
</div>

{{--<!-- Venue Field -->--}}
{{--<div class="form-group">--}}
    {{--{!! Form::label('venue', 'Venue:') !!}--}}
    {{--<p>{!! $setlist->venue !!}</p>--}}
{{--</div>--}}

<!-- Url Field -->
<div class="form-group">
    {!! Form::label('url', 'Url:') !!}
    <p>{!! $setlist->url !!}</p>
</div>

<!-- Is Utilized Field -->
<div class="form-group">
    {!! Form::label('is_utilized', 'Is Utilized:') !!}
    <p>{!! $setlist->is_utilized !!}</p>
</div>

