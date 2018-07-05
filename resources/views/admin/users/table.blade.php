<table class="table table-bordered table-sm table-hover" id="users-table">
    <thead>
        <th>ID</th>
        <th>Username</th>
        <th>Avatar</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{!! $user->id !!}</td>
            <td>{!! $user->username !!}</td>
            <td>{!! $user->avatar !!}</td>
            <td>
                {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('users.show', [$user->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>View</a>
                    <a href="{!! route('users.edit', [$user->id]) !!}" class='btn btn-default btn-sm btn-outline-secondary'>Edit</a>
                    {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
