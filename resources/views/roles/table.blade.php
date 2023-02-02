@php
 $access = getUserModuleAccess('roles'); 
 @endphp
<h5 class="pvr-header">
    Roles
    <div class="pvr-box-controls">
        <i class="material-icons mr-3" data-box="fullscreen">fullscreen</i>
        @if($access->create ==1)
            <a class="btn btn-purple pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('roles.create') }}">Add New</a>
        @endif
    </div>
</h5>
    <table id="data-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($roles as $roles)
            <tr>
                <td>{{ $roles->name }}</td>
                <td>
                    {!! Form::open(['route' => ['roles.destroy', $roles->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                    @if($access->edit ==1)
                        <a href="{{ route('roles.edit', [$roles->id]) }}" class='btn btn-outline-success btn-xs'><i class="fa fa-edit"></i></a>
                    @endif
                    @if($access->remove ==1)
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure you want to delete. It might affect users if you have used?')"]) !!}
                    @endif
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
