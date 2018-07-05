<table class="table table-responsive" id="setlists-table">
    <thead>
        <th>Date</th>
        <th>Venue</th>
        <th>Url</th>
        <th>Is Utilized</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($setlists as $setlist)
        <tr>
            <td>{!! $setlist->date !!}</td>
            <td>{!! $setlist->venue !!}</td>
            <td>{!! $setlist->url !!}</td>
            <td>{!! $setlist->is_utilized !!}</td>
            <td>
                {!! Form::open(['route' => ['setlists.destroy', $setlist->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('setlists.show', [$setlist->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('setlists.edit', [$setlist->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
