@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="pvr-wrapper">
            <div class="pvr-box">
                <h5 class="pvr-header pull-left">
            Sub Admin
        </h5>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('subAdmins.create') }}">Add New</a>
        </h1>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('sub_admins.table')
            </div>
        </div>
        <div class="text-center">
        
        </div></div></div></div></section>
@endsection

