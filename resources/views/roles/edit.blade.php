@extends('layouts.app')

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="pvr-wrapper">
        <div class="pvr-box">
          <h5 class="pvr-header">
              Update Roles
          </h5>
             {!! Form::model($roles, ['route' => ['roles.update', $roles->id], 'method' => 'patch']) !!}
          <div class="row">
                  @include('roles.fields')
           </div>
             {!! Form::close() !!}
         </div>
     </div>
 </div>
@endsection