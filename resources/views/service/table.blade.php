@php
 $access = getUserModuleAccess('service'); 
 @endphp
<h5 class="pvr-header">
                        Service
                        <div class="pvr-box-controls">
                            <i class="material-icons mr-3" data-box="fullscreen">fullscreen</i>
                    @if($access->create ==1)
                            <a class="btn btn-purple pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('service.create') }}">Add New</a>
                    @endif
                        </div>
                    </h5>
<table id="data-table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody> @foreach($services as $service)
        <tr>
            <td>{{ $service->name }}</td>
            <td>{{ $service->price }}</td>
            <td>{{ $service->description }}</td>
            <td> {!! Form::open(['route' => ['service.destroy', $service->id], 'method' => 'delete']) !!}
                <div class='btn-group'> 
                    @if($access->view ==1)
                    <a title="View" href="{{ route('service.show', [$service->id]) }}" class='btn btn-outline-dark btn-xs'><i class="fa fa-eye"></i></a> 
                    @endif
                    @if($access->edit ==1)
                    <a title="Edit" href="{{ route('service.edit', [$service->id]) }}" class='btn btn-outline-primary btn-xs'><i class="fa fa-edit"></i></a> 
                    <a title="Translate" href="{{ route('service.translate', [$service->id]) }}" class='btn btn-outline-info btn-xs'><i class="fa fa-language"></i></a> 
                    @endif
                    @if($access->remove ==1)
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit','title' => 'Delete','class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} {!! Form::close() !!} 
                    @endif
                </div>
            </td>
        </tr> @endforeach </tbody>
</table>