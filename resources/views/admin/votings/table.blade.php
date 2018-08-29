<table class="table table-bordered table-xs table-hover" id="votings-table">
    <thead>
        <th>Name</th>
        <th>Description</th>
        <th>Songs</th>
        <th>Is Open</th>
        <th>Expires On</th>
        <th>Type</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($votings as $voting)
        <tr>
            <td>{!! $voting->name !!}</td>
            <td>{!! $voting->description !!}</td>
            <td>{!! count($voting->songs) !!}</td>
            <td>{!! $voting->is_open !!}</td>
            <td>{!! $voting->expires_on !!}</td>
            <td>{!! $voting->type !!}</td>
            <td>
                {!! Form::open(['route' => ['votings.destroy', $voting->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('votings.show', [$voting->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>View</a>
                    <a href="{!! action('Admin\VotingController@calculate', [$voting->id, 'public' => '1']) !!}" class='btn btn-default btn-sm btn-outline-secondary'>Calculate Public</a>
                    <a href="{!! action('Admin\VotingController@calculate', [$voting->id, 'public' => '0']) !!}" class='btn btn-default btn-sm btn-outline-secondary'>Calculate Private</a>
                    <a href="{!! action('Admin\VotingController@showStats', [$voting->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>Stats</a>
                    <a href="{!! route('votings.edit', [$voting->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>Edit</a>
                    {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
