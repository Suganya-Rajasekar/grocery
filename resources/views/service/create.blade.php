@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="pvr-wrapper">
            <div class="pvr-box">
                <h5 class="pvr-header">
                    Service
                </h5>
                    {!! Form::open(['route' => 'service.store','enctype'=>'Multipart/form-data']) !!}
                <div class="row">

                        @include('service.fields')

                </div>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
