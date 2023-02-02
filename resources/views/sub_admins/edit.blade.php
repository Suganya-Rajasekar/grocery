@extends('layouts.app')

@section('content')
   <section class="content-header">
      <div class="pvr-wrapper">
            <div class="pvr-box">
                <h5 class="pvr-header">
            Sub Admin
        </h5>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
                   {!! Form::model($subAdmin, ['route' => ['subAdmins.update', $subAdmin->id], 'method' => 'patch']) !!}

                        @include('sub_admins.fields')

                   {!! Form::close() !!}
           </div>
       </div>
   </div></div></div></section>
@endsection