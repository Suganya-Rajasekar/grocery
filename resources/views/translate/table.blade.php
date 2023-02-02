@php
 $access = getUserModuleAccess('translate'); 
 @endphp
<h5 class="pvr-header">
                        Translate
                        <div class="pvr-box-controls">
                            <i class="material-icons mr-3" data-box="fullscreen">fullscreen</i>
                    @if($access->create ==1)
                            <a class="btn btn-purple pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('translate.translate', [0]) }}">Add New</a>
                    @endif
                        </div>
                    </h5>
<table id="data-table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Key</th>
            <th>Content</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody> @foreach($translates as $translate)
        <tr>
            <td>{{ $translate->key }}</td>
            <td>{{ $translate->content }}</td>
            <td> {!! Form::open(['route' => ['translate.destroy', $translate->id], 'method' => 'delete']) !!}
                <div class='btn-group'> 
                    @if($access->edit ==1)
                    <a title="Translate" href="{{ route('translate.translate', [urlencode($translate->key)]) }}" class='btn btn-outline-info btn-xs'><i class="fa fa-language"></i></a> 
                    @endif
                </div>
            </td>
        </tr> @endforeach </tbody>
</table>