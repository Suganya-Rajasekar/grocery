@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="pvr-wrapper">
            <div class="pvr-box">
                <h5 class="pvr-header">
                    Translate
                </h5>
                    {!! Form::open(['route' => 'translate.store','enctype'=>'Multipart/form-data']) !!}
                <div class="row">

                        @include('translate.fields')

                </div>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
