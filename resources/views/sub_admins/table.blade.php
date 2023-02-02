<div class="table-responsive">
    <table class="table" id="subAdmins-table">
        <thead>
            <tr>
                <th>Role</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Business Name</th>
        <th>Address</th>
        <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($subAdmins as $subAdmin)
            <tr>
                <td>{{ $subAdmin->role }}</td>
            <td>{{ $subAdmin->name }}</td>
            <td>{{ $subAdmin->email }}</td>
            <td>{{ $subAdmin->phone_number }}</td>
            <td>{{ $subAdmin->business_name }}</td>
            <td>{{ $subAdmin->address }}</td>
            <td>{{ $subAdmin->status }}</td>
                <td>
                    {!! Form::open(['route' => ['subAdmins.destroy', $subAdmin->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('subAdmins.edit', [$subAdmin->id]) }}" class='btn btn-outline-success btn-xs'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
