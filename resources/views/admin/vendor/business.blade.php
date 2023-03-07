@extends('layouts.backend.app')
@section('page_header')
<?php
    $chef   = getUserData(\Request::segment(3));
    $cpage  = request()->has('page') ? request()->get('page') : '';
    $from   = request()->has('from') ? request()->get('from') : '';
    $url    = '?from='.$from.'&page='.$cpage;
?>
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h5><span class="text-semibold">@if(getRoleName() == 'admin'){!! $chef->name !!}@else{!! 'Business Info' !!}@endif</span> - @if(getRoleName() == 'admin'){!! 'Business Info' !!}@else{!! 'Edit Business Info' !!}@endif</h5>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            @if(getRoleName() == 'admin')
            <li><a href="{!! url('admin/chef'.$url) !!}">All chefs</a></li>
            @endif
            <li class="active">Edit business info</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content">
    @if(getRoleName() == 'admin')
    <div class="row">
        <div class="col-md-2">
            <div class="online-switch-asw mr-2">
                <legend class="text-semibold">
                    Chef mode
                </legend>
                <label class="switch mb-0">
                    <input type="checkbox" id="togBtn" name="online_mode" @if(isset($restaurant->mode) && $restaurant->mode=='open') checked @endif>
                    <div class="slider round">
                        <span class="on">ONLINE</span>
                        <span class="off">OFFLINE</span>
                    </div>
                </label>
            </div>
        </div>
        <br>
        <div class="col-md-10">
            @endif
            @if($restaurant->home_event == 'no')
            <form action="{!!url(getRoleName().'/schedule')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data" id="scheduleform">
                {{ csrf_field() }}{{ method_field('POST') }}
                @if(getRoleName() == 'vendor') 
                <div class="row">
                    <div class="col-md-12">
                        @endif
                        <div class="panel panel-flat">
                            {{-- <div class="panel-heading">
                                <h5 class="panel-title">Scheduling details</h5>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                    </ul>
                                </div>
                            </div> --}}
                            <input type="hidden" name="id" value="{{ $restaurant ? $restaurant->id : '' }}">
                            <input type="hidden" name="v_id" id="v_id" value="{!!isset($restaurant->vendor_id) ? $restaurant->vendor_id : '' !!}">
                            <input type="hidden"  class="form-control" name="area_code" id="area_code" value="" disabled="">
                            <div class="panel-body business-info-asw">
                                <fieldset>
                                    <legend class="text-semibold">
                                        <i class="fa fa- clock-o position-left"></i>
                                        Schedule Off time in Advance
                                        <a class="control-arrow" data-toggle="collapse" data-target="#demo1">
                                            <i class="icon-circle-down2"></i>
                                        </a>
                                    </legend>
                                    <div class="row collapse in show" id="demo1">
                                        <div class="form-group col-md-6">
                                            {{-- <label class="text-semibold">Off time in Advance :</label> --}}
                                            <div class="input-group align-items-center">
                                                <span class="input-group-addon calendar-icon-asw"><i class="icon-calendar22"></i></span>
                                                <input type="text" name="date_range" class="form-control daterange-time" value="@if(!empty($restaurant->off_from) && !empty($restaurant->off_to)) {{ date('Y-m-d h:i A',strtotime($restaurant->off_from)).' - '.date('Y-m-d h:i A',strtotime($restaurant->off_to)) }} @else {!! date('Y-m-d').' - '.date('Y-m-d') !!} @endif"> 
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <button type="submit" class="btn btn-primary font-monserret mr-2">Submit<i class="icon-arrow-right14 position-right"></i></button>
                                            <a href="{!! \Request::fullUrl() !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                                            <a href="{!! url(getRoleName().'/offtimelog?chef_id='.$restaurant->vendor_id) !!}"  class="btn btn-info font-monserret ml-2"><i class="fa fa-toggle-off position-left"></i>Offtime Log</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        @if(getRoleName() == 'vendor')
                    </div>
                </div>
                @endif
            </form>
            <form action="{!!url(getRoleName().'/availability')!!}" method="POST" class="form-horizontal operational_hour" enctype="Multipart/form-data" id="availform">
                {{ csrf_field() }}{{ method_field('POST') }}
                @if(getRoleName() == 'vendor')
                <div class="row">
                    <div class="col-md-12">
                        @endif
                        <div class="panel panel-flat">
                            <input type="hidden" name="id" value="{{ $restaurant ? $restaurant->id : '' }}">
                            <input type="hidden" name="v_id" id="v_id" value="{!!isset($restaurant->vendor_id) ? $restaurant->vendor_id : '' !!}">
                            <input type="hidden"  class="form-control" name="area_code" id="area_code" value="" disabled="">
                            <div class="panel-body business-info-asw">
                                <fieldset>
                                    <legend class="text-semibold">
                                        <i class="fa fa- clock-o position-left"></i>
                                        Operational Hours
                                        <a class="control-arrow" data-toggle="collapse" data-target="#demo2">
                                            <i class="icon-circle-down2"></i>
                                        </a>
                                    </legend>
                                    <div class="collapse in show" id="demo2">
                                        <div class="row">
                                            {{-- <div class="form-group col-md-12 mb-0">
                                                <label class="text-semibold">Timeslot - 1</label>
                                            </div> --}}
                                            <div class="form-group col-md-3">
                                                <div class="input-group align-items-center">
                                                    <span class="input-group-addon calendar-icon-asw">From :</span>
                                                    <input type="text" name="opening_time" class="form-control pickatime" value="{!! $restaurant->opening_time !!}"> 
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <div class="input-group align-items-center">
                                                    <span class="input-group-addon calendar-icon-asw">To :</span>
                                                    <input type="text" name="closing_time" class="form-control pickatime" value="{!! $restaurant->closing_time !!}"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                           {{--  <div class="form-group col-md-12 mb-0">
                                                <label class="text-semibold">Timeslot - 2</label>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <div class="input-group align-items-center">
                                                    <span class="input-group-addon calendar-icon-asw">From :</span>
                                                    <input type="text" name="opening_time2" class="form-control pickatime" value="{!! $restaurant->opening_time2 !!}"> 
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <div class="input-group align-items-center">
                                                    <span class="input-group-addon calendar-icon-asw">To :</span>
                                                    <input type="text" name="closing_time2" class="form-control pickatime" value="{!! $restaurant->closing_time2 !!}"> 
                                                </div>
                                            </div> --}}
                                            <div class="form-group col-md-6">
                                                <button type="submit" class="btn btn-primary font-monserret mr-2">Submit<i class="icon-arrow-right14 position-right"></i></button>
                                                <a href="{!! \Request::fullUrl() !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        @if(getRoleName() == 'vendor')
                    </div>
                </div>
                @endif
            </form>
            <form action="{!!url(getRoleName().'/working_days')!!}" method="POST" class="form-horizontal unavail_days" enctype="Multipart/form-data" id="availform">
                {{ csrf_field() }}{{ method_field('POST') }}
                @if(getRoleName() == 'vendor')
                <div class="row">
                    <div class="col-md-12">
                        @endif
                        <div class="panel panel-flat">
                            <input type="hidden" name="id" value="{{ $restaurant ? $restaurant->id : '' }}">
                            <input type="hidden" name="v_id" id="v_id" value="{!!isset($restaurant->vendor_id) ? $restaurant->vendor_id : '' !!}">
                            <input type="hidden"  class="form-control" name="area_code" id="area_code" value="" disabled="">
                            <div class="panel-body business-info-asw">
                                <fieldset>
                                    <legend class="text-semibold">
                                        <i class="fa fa- clock-o position-left"></i>
                                        Schedule Off days in Advance
                                        <a class="control-arrow" data-toggle="collapse" data-target="#demo2">
                                            <i class="icon-circle-down2"></i>
                                        </a>
                                    </legend>
                                    <div class="collapse in show" id="demo2">
                                            {{-- <div class="form-group col-md-12 mb-0">
                                                <label class="text-semibold">Timeslot - 1</label>
                                            </div> --}}
                                        <div class="input-group align-items-center">
                                            <?php 
                                            $week   = array('sunday','monday' ,'tuesday','wednesday' ,'thursday' ,'friday' ,'saturday');
                                            $workdays   = json_decode($restaurant->working_days);
                                            $dds =[];
                                            if($workdays){
                                                foreach($workdays as $kk=>$val){
                                                    if($val==1){
                                                        $dds[]= $kk;
                                                    } 
                                                }
                                            }
                                            ?>
                                            {{-- //foreach is more readable --}}
                                            @foreach ($week as $dayName)
                                            {{-- //Check if current week day is in real_days --}}
                                            <?php 
                                                $checked = '';
                                                if (in_array($dayName, $dds)) {
                                                    $checked = ' checked="checked" ';
                                                }
                                            ?>
                                            <div class='mr-3'>
                                                <label>
                                                <input type='checkbox' name='working_days[]' value="{!! $dayName !!}" {!! $checked !!}>
                                                {!! ucfirst($dayName) !!}<br>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 mt-3">
                                                <button type="submit" class="btn btn-primary font-monserret mr-2">Submit<i class="icon-arrow-right14 position-right"></i></button>
                                                <a href="{!! \Request::fullUrl() !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        @if(getRoleName() == 'vendor')
                    </div>
                </div>
                @endif
            </form>
            @if(getRoleName() != 'vendor')
            <div class="package_field">
            <form action="{!!url('/packages')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
                {{ csrf_field() }}{{ method_field('POST') }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <input type="hidden" name="v_id" id="v_id" value="{!!isset($restaurant->vendor_id) ? $restaurant->vendor_id : '' !!}">
                            <div class="panel-body business-info-asw">
                                <fieldset>
                                    <div class="row collapse in show" id="demo1">
                                        <div class="form-group col-md-3">
                                            <label for="package">Packages</label>
                                            <select class="form-control" name="package" id="package">
                                                <option>Select anyone</option>
                                                <option @if($user->package == 'silver') selected @endif>Silver</option>
                                                <option @if($user->package == 'gold') selected @endif>Gold</option>
                                                <option @if($user->package == 'platinum') selected @endif>Platinum</option>
                                            </select>  
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="amount">Amount</label>
                                            <select class="form-control" name="amount" id="amount">
                                                <option>Select anyone</option>
                                                <option @if($user->amount == 0) selected @endif>0</option>
                                                <option @if($user->amount == 1000) selected @endif>1000</option>
                                                <option @if($user->amount == 1500) selected @endif>1500</option>
                                                <option @if($user->amount == 3500) selected @endif>3500</option>
                                                <option @if($user->amount == 6000) selected @endif>6000</option>
                                            </select>
                                        </div>             
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-primary font-monserret mr-2 mt-20">Submit<i class="icon-arrow-right14 position-right"></i></button>
                                            <a href="{!! \Request::fullUrl() !!}" class="btn btn-danger font-monserret mt-20">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div> 
                </div>
            </form>
            </div>
        @endif
        </div>
    </div>
    @endif
</div>

    <!-- Form horizontal -->    
    <form action="{!!url(getRoleName().'/chef/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data" id="business_form">
        {{ csrf_field() }}{{ method_field('PUT') }}
        <input type="hidden" name="type" id="res_type" @if($restaurant->home_event == 'no') value="{{ isset($restaurant->type) ? $restaurant->type : '' }}" @else value="home_event" @endif>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Basic details</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                {{-- <li><a data-action="reload"></a></li>
                                <li><a data-action="close"></a></li> --}}
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ $restaurant ? $restaurant->id : '' }}">
                    <input type="hidden" name="v_id" id="v_id" value="{!!isset($restaurant->vendor_id) ? $restaurant->vendor_id : '' !!}">
                    <input type="hidden"  class="form-control" name="area_code" id="area_code" value="" disabled="">
                    <div class="panel-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="text-semibold">Business profile name</label>
                                <input type="text"  class="form-control" name="profile_name" placeholder="Enter name"  id="name" value="{!! isset($restaurant->name) ? $restaurant->name : old('name') !!}">
                            </div>
                            <div class="form-group" id="tags_field">
                                <label class="text-semibold">Tags</label>
                                <select data-placeholder="Select Tags" name="tags[]" class="select-search" multiple=''>
                                    <option value="" hidden disabled>Select Tags</option>
                                    @if(count($tags)>0)
                                    @foreach($tags as $key=>$value)
                                    <option @if(isset($restaurant->tag) && in_array($value->id,explode(',',$restaurant->tag))) selected @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @if($restaurant->home_event == 'no')
                            <div class="form-group" id="cost_field">
                                <label class="text-semibold">Cost for two</label>{{-- 
                                <div class="col-lg-10">
                                    @if(count($budget)>0)
                                    @foreach($budget as $key=>$value)
                                    <label class="radio-inline">
                                        <input type="radio" name="budget" value="{{ $value->id }}" class="styled"  @if(isset($restaurant->budget)) {!! ($restaurant->budget==$value->id) ? 'checked="checked"' : ''  !!}  @else checked  @endif >
                                        {!!$value->name!!}
                                    </label>
                                    @endforeach
                                    @endif
                                </div> --}}
                                <input type="number" class="form-control" name="budget" id="budget" value="{!! (isset($restaurant->budget)) ? $restaurant->budget : old('budget') !!}">
                            </div>
                            <div class="form-group" id="fssai_field">
                                <label class="text-semibold">FSSAI Number</label>
                                <input type="digits" minlength="14" maxlength="14" class="form-control numberonly" name="fssai" id="fssai" value="{!! isset($restaurant->fssai) ? $restaurant->fssai : old('fssai') !!}" required="">
                            </div>
                            <div class="form-group" id="preparation_field">
                                <label class="display-block text-semibold">Chef Preparation Time</label>
                                <label class="radio-inline">
                                    <input type="radio" name="preparation_time" value="ondemand" class="styled" {!! (isset($restaurant->preparation_time) && $restaurant->preparation_time == 'ondemand') ? 'checked="checked"' : ''  !!}>
                                    On Demand Chef
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="preparation_time" value="preorder" class="styled" {!! (isset($restaurant->preparation_time) && $restaurant->preparation_time == 'preorder') ? 'checked="checked"' : ''  !!}>
                                    Pre Order Chef
                                </label>
                            </div>
                            @endif
                            @if($restaurant->type == 'event')
                            <div class="form-group">
                                <label class="text-semibold">Event DateTime</label>
                                <input type="text" class="form-control" name="event_date_time" id="event_date" autocomplete="off" value="{{ isset($restaurant->event_datetime) ? $restaurant->event_datetime : ''}}">
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6" id="gst_div">
                                    <div class="form-group">
                                        <label class="text-semibold">GST (%)</label>
                                        <input type="number"  class="form-control" name="tax" value="{!! isset($restaurant->tax) ? $restaurant->tax : old('tax') !!}" min="0" max="100">
                                    </div>
                                </div>
                                @if(\Auth::user()->role == 1 ||\Auth::user()->role == 5)
                                <div class="col-md-6" id="commission_field">
                                    <div class="form-group">
                                        <label class="text-semibold">Commission (%)</label>
                                        <input type="number"  class="form-control" name="commission" value="{!! isset($restaurant->commission) ? $restaurant->commission : old('commission') !!}" min="0" max="100">
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if($restaurant->home_event == 'no')
                            <div class="form-group" id="pack_charge">
                                <label class="text-semibold">Packaging Charge (Rs)</label>
                                <input type="number"  class="form-control" name="package_charge" value="{!! isset($restaurant->package_charge) ? $restaurant->package_charge : old('package_charge') !!}" >
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="text-semibold">Description</label>
                                <textarea rows="5" cols="5" class="form-control limitcount" name="description" placeholder="Enter Description" required >{!!isset($restaurant->description) ? $restaurant->description : old('description') !!}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Select City</label>
                                <select name="location" class="select-search location" required data-placeholder="Select city">
                                    <option value="" hidden disabled>Select city</option>
                                    @if(count($city) > 0)
                                    @foreach($city as $key=>$value)
                                    <option value="{!! $value->id !!}" @if(isset($restaurant->location) && $value->id == $restaurant->location) selected @endif>{!! $value->name !!}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Address details</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                {{-- <li><a data-action="reload"></a></li> --}}
                                {{-- <li><a data-action="close"></a></li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div id="account_field">
                            @if(getRoleName() != 'vendor')
                                @if(!empty($restaurant->rz_account))
                                <div class="form-group">
                                   <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_bank">Bank Account<i class="icon-piggy-bank position-right"></i></button>
                                </div>
                                @else
                                <div class="form-group">
                                   <button type="button" class="btn btn-primary createRzAccount" data-id="{!! $restaurant->vendor_id !!}">Create Razor Account<i class="icon-file-plus position-right"></i></button>
                                </div>
                                @endif
                            @endif
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Complete Address</label>
                                <input type="text"  class="form-control" name="adrs_line_1" placeholder="Enter complete address" id="adrs_line1" value="{!! isset($restaurant->adrs_line_1) ? $restaurant->adrs_line_1 : old('adrs_line_1') !!}" required="">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-semibold">Locality</label>
                                        <input type="text" class="form-control" name="locality" placeholder="Enter locality" id="adrs_line2" value="{!! isset($restaurant->locality) ? $restaurant->locality : old('locality') !!}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-semibold">Landmark</label>
                                        <input type="text" class="form-control" name="landmark" placeholder="Enter landmark" id="adrs_line2" value="{!! isset($restaurant->landmark) ? $restaurant->landmark : old('landmark') !!}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="location">
                                <label class="text-semibold">Location</label>
                                <input type="text" id="txtPlaces" class="form-control" name="adrs_line_2" placeholder="Enter location" id="adrs_line1" value="{!! isset($restaurant->adrs_line_2) ? $restaurant->adrs_line_2 : old('adrs_line_2') !!}" required>
                            </div>
                            <div class="row" id="lat_lang">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-semibold">Latitude</label>
                                        <input type="text"  class="form-control" name="latitude" placeholder="Enter latitude" id="lat" value="{!! isset($restaurant->latitude) ? $restaurant->latitude : old('latitude') !!}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-semibold">Longitude</label>
                                        <input type="text"  class="form-control" name="longitude" placeholder="Enter longitde" id="lang" value="{!! isset($restaurant->longitude) ? $restaurant->longitude : old('longitude') !!}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="sector">
                                <label class="text-semibold">Sector</label>
                                <input type="text"  class="form-control" name="sector" placeholder="Enter sector" value="{{ isset($restaurant->sector) ? $restaurant->sector : '' }}" >
                            </div>
                            {{-- <div id="txtPlaces"></div> --}}
                            <div id="myMap"></div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="text-right">
                    <a href="@if(!isset($restaurant)){!! url('admin/chef') !!}@else{!! url('admin/chef/request') !!}@endif" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                    <button type="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>        
        </div>
    </form>
    <!-- /form horizontal -->
@if(getRoleName() != 'vendor' && !empty($restaurant->rz_account))
<div id="modal_bank" class="modal">
    <div class="modal-dialog" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bank Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <button type="button" class="btn btn-outline-primary btn-lg btn-edit"><i class="icon-pencil3 "></i></button>
            <fieldset disabled>
                <form action="{!! route('admin.fundaccount') !!}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <input value="{!! $restaurant->rz_account->contact !!}" type="hidden" name="contact_id" id="contact_id" required="">
                                <div class="col-sm-6">
                                    <label>Beneficiary Name</label>
                                    <input value="{!! $restaurant->rz_account->name !!}" type="text" name="b_name" id="b_name" placeholder="Beneficiary Name" class="form-control" required="">
                                </div>

                                <div class="col-sm-6">
                                    <label>Account Number</label>
                                    <input value="{!! $restaurant->rz_account->account_number !!}" type="text" name="account_number" id="account_number" placeholder="Account Number" class="form-control" required="">
                                </div>

                                <div class="col-sm-6">
                                    <label>Ifsc Code</label>
                                    <input value="{!! $restaurant->rz_account->ifsc_code !!}" type="text" name="ifsc_code" id="ifsc_code" placeholder="Ifsc Code" class="form-control" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form> 
            </fieldset>
        </div>
    </div>
</div>
@endif
<style>
    fieldset:disabled{
        opacity: 0.5;
        user-select: none;
        pointer-events: none;
    }
    .btn-edit {
        position: absolute;
        top: 43%;
        left: 40%;
        z-index: 1;
    }
    #myMap {max-width:100%;height: 350px;width: 920px;z-index:1;}
</style>
<!-- /content area -->
@endsection
@section('script')
    
<script async defer src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAUqxCzqXHg1jeS_RUd4p4ukmVrcXckxYA&callback=initialize" type="text/javascript"></script>

<script type="text/javascript">
    $('#modal_bank').on('hidden.bs.modal', function () {
        $('#modal_bank fieldset').attr('disabled',true);
        $('.btn-edit').fadeIn();
    });
    $('.btn-edit').click(function(){
        $('#modal_bank fieldset').attr('disabled',false);
        $(this).fadeOut();
    });
    $('.createRzAccount').click(function(){
        var chef = $(this).attr('data-id');
        var token   = $("input[name='_token']").val();
        var url     = '{!!url(getRoleName()."/createRzAccount")!!}';
        $.ajax({
            type    : 'POST',
            url     : url,
            data    : {chef:chef,"_token": token},
            success : function(res){
                location.reload(); 
            },
            error   : function(err){
            }
        });
    }) 

    $('.pickatime').pickatime();
    // Display time picker
    $('.daterange-time').daterangepicker({
        timePicker: true,
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-danger',
        locale: {
            format: 'YYYY-MM-DD h:mm a'
        }
    });
    $(".styled").uniform({
        radioClass: 'choice'
    });
    function initialize(){
        var map;
        var marker;
        var lat     = $('#lat').val();
        var lang    = $('#lang').val();
        var myLatlng= new google.maps.LatLng(lat,lang);
        var geocoder= new google.maps.Geocoder();
        const input = document.getElementById("txtPlaces");
        // var infowindow = new google.maps.InfoWindow();
        var mapOptions  = {
            zoom: 15,
            center: new google.maps.LatLng(lat,lang),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map     = new google.maps.Map(document.getElementById("myMap"), mapOptions);
        marker  = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(lat,lang),
            draggable: true
        });

        const options = {
            // componentRestrictions: { country: "us" },
            fields: ["formatted_address", "geometry", "name"],
            origin: map.getCenter(),
            strictBounds: false,
            types: ["establishment"],
        };
        // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
        const autocomplete  = new google.maps.places.Autocomplete(input, options);
        autocomplete.bindTo("bounds", map);
        const infowindow    = new google.maps.InfoWindow();
        const infowindowContent = document.getElementById("infowindow-content");
        infowindow.setContent(infowindowContent);
        autocomplete.addListener("place_changed", () => {
            infowindow.close();
            marker.setVisible(false);
            const place = autocomplete.getPlace();
            var latitude  = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            $('#lat').val(latitude);
            $('#lang').val(longitude);

            if (!place.geometry || !place.geometry.location) {
                window.toast("No details available for input: '" + place.name + "'",'Error!', 'error');
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
            infowindowContent.children["place-name"].textContent = place.name;
            infowindowContent.children["place-address"].textContent =
            place.formatted_address;
            infowindow.open(map, marker);
        });

        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#txtPlaces').val(results[0].formatted_address);
                    $('#lat').val(marker.getPosition().lat());
                    $('#lang').val(marker.getPosition().lng());
                    var components = results[0].address_components;
                }
            }
        });
        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('#txtPlaces').val(results[0].formatted_address);
                        $('#lat').val(marker.getPosition().lat());
                        $('#lang').val(marker.getPosition().lng());
                        var components = results[0].address_components;
                    }
                }
            });
        });
        google.maps.event.addListener(map, 'click', function (event) {
            $('#mlatitude').val(event.latLng.lat());
            $('#mlongitude').val(event.latLng.lng());
            placeMarker(event.latLng);
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('#txtPlaces').val(results[0].formatted_address);
                        $('#lat').val(marker.getPosition().lat());
                        $('#lang').val(marker.getPosition().lng());
                        var components = results[0].address_components;
                    }
                }
            });
        });
    }

    function placeMarker(location) {
        if (marker == undefined){
            marker = new google.maps.Marker({
                position: location,
                map: map, 
                animation: google.maps.Animation.DROP,
            });
        } else {
            marker.setPosition(location);
        }
        map.setCenter(location);
    }

    $(document).on('change','.location',function(e) {
        e.preventDefault();
        var location= $(this).val(); 
        var token   = $("input[name='_token']").val();
        var url     = base_url+"/admin/location_code";
        $.ajax({
            type    : 'POST',
            url     : url,
            data    : {location:location,"_token": token},
            success : function(res){
                $('#area_code').val(res);
            },
            error   : function(err){
            }
        });
    });

    $(document).ready(function(){
    // $('#event_date').datetimepicker();
        if($('#res_type').val() == 'event') {
            $('#tags_field,#preparation_field,#fssai_field,#commission_field,#account_field,#lat_lang,#location,#myMap,#sector').hide();
            $('#pack_charge,#cost_field').hide();
            $('.package_field').hide();
            $('.operational_hour').hide();
            $('.unavail_days').hide();
            $('#gst_div').removeClass('col-md-6').addClass('col-md-12');
            $('#business_form').attr('novalidate',true);
        }
    });

    $('#event_date').datetimepicker({
       format: 'Y-m-d H:i:s',
       formatTime:"h:i a",
       step:30,
       minDate:0,
       onSelectTime:function(curren_time,input){
        let datetime = input.val().split(" ");
        let time = datetime[1].split(":");
        time[2]  = '00';
        let changed_time = datetime[0]+" "+time[0]+":"+time[1]+":"+time[2];
        $('#event_date').val(changed_time);
       }
   });
    
    //save schedule
    /*$(document).on('submit','#scheduleform',function(e){
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : base_url+'/vendor/schedule',
            data : $("#scheduleform").serialize(),
            success : function(res){
                $('.modal').modal('hide');
                var msg = JSON.parse(JSON.stringify(res)); 
                $(".error-message-area").css("display","block");
                $(".error-content").css("background","#d4d4d4");
                $(".error-msg").html("<p style='color:red' class='mb-0'>"+msg.message+"</p>"); 
                setTimeout(function(){location.reload()}, 1000);
            },
            error : function(err){
                $('.modal').modal('hide');
                var msg = err.responseJSON.message; 
                $(".error-content").css("background","#d4d4d4");
                $(".error-message-area").find('.error-msg').text(msg);
                $(".error-message-area").show();
            }
        });
    });*/
</script>
@endsection
