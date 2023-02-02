@extends('layouts.app')

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="pvr-wrapper">
            <div class="pvr-box">
                <h5 class="pvr-header">
                    Site Settings
                </h5>
                   {!! Form::model($setting, ['route' => ['setting.update', $setting->id], 'method' => 'patch','enctype'=>'Multipart/form-data']) !!}
                <div class="row">

                        @include('setting.fields')

               </div>
                   {!! Form::close() !!}
           </div>
       </div>
   </div>
@endsection