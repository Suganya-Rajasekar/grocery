@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="pvr-wrapper">
        <div class="pvr-box">
          <h5 class="pvr-header">
              Create Role
          </h5>
            {!! Form::open(['route' => 'roles.store']) !!}
             <div class="row">
                @include('roles.fields')
             </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
