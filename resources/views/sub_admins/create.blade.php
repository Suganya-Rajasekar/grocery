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
        <div class="">
            <div class="">
                <div class="row">
                    {!! Form::open(['route' => 'subAdmins.store']) !!}

                        @include('sub_admins.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div></div></div></section>
@endsection
