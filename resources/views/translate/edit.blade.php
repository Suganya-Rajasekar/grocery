@extends('layouts.app')

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="pvr-wrapper">
            <div class="pvr-box">
                <h5 class="pvr-header">
                    Update Category
                </h5>
                   {!! Form::model($category, ['route' => ['Category.update', $category->id], 'method' => 'patch','enctype'=>'Multipart/form-data']) !!}
                <div class="row">

                        @include('category.fields')

               </div>
                   {!! Form::close() !!}
           </div>
       </div>
   </div>
@endsection