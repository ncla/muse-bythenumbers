<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::date('date', null, ['class' => 'form-control']) !!}
</div>

<!-- Venue Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('venue', 'Venue:') !!}
    {!! Form::textarea('venue', null, ['class' => 'form-control', 'rows' => '5']) !!}
</div>


<!-- Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('url', 'Url:') !!}
    {!! Form::text('url', null, ['class' => 'form-control']) !!}
</div>

<!-- Is Utilized Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_utilized', 'Is Utilized:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('is_utilized', false) !!}
        {!! Form::checkbox('is_utilized', 1, null) !!} $VALUE$
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('setlists.index') !!}" class="btn btn-default">Cancel</a>
</div>
