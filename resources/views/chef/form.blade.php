@extends('layouts.backend.app')
@section('page_header')
<?php
    $cpage  = request()->has('page') ? request()->get('page') : '';
    $from   = request()->has('from') ? request()->get('from') : '';
    $url    = '?from='.$from.'&page='.$cpage;
?>
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title d-flex align-items-center">
            <h5><span class="text-semibold">@if($id == ''){!! 'All chefs' !!}@elseif($chefpage)@if(getRoleName() == 'admin'){!! $chefs->name !!}@else {!! 'Profile' !!}@endif @else{!! 'Chefs request' !!}@endif</span> - @if($id == ''){!! 'Add new ' !!}@elseif($chefpage)@if(getRoleName() == 'admin'){!! 'Edit Chef' !!}@else {!! 'Edit Profile' !!}@endif @else{!! 'Chef request approval' !!}@endif         
            </h5>
            @if(getRoleName() == 'admin')
            <span class="type-switch-asw mx-2" @if(isset($chefs->id) && $chefs->id != '') style="display:none;" @endif>
                {{-- <legend class="text-semibold">Type</legend> --}}
                <label class="switch mb-0">
                    <input type="checkbox" name="type" class="typebtn" @if(isset($chefs->type) && $chefs->type == 'event') checked @endif>
                    <div class="slider round">
                        <span class="event">EVENT</span>
                        <span class="chef">CHEF</span>
                    </div>
                </label>
            </span>
            @endif
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            @if(getRoleName() == 'admin')
            <li><a href="@if($id == ''){!! url('admin/chef'.$url) !!}@elseif($chefpage){!! url('admin/chef'.$url) !!}@else{!! url('admin/chef/request'.$url) !!}@endif">@if($id == ''){!! 'All chefs' !!}@elseif($chefpage){!! 'All chefs' !!}@else{!! 'Chefs request' !!}@endif</a></li>
            @endif
            <li class="active" id="breadcrumb_activetext">@if($id == ''){!! 'Add new Chef' !!}@elseif($chefpage){!! 'Edit chef' !!}@else{!! 'Chef request approval' !!}@endif</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content">
    <form action="{!!url(getRoleName().'/chef/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data" id="chef_form">
        {{ csrf_field() }}{{ method_field('PUT') }}
        <input autocomplete="false" name="hidden" type="text" class="hidden">
        <input type="hidden" name="c_id" id="c_id" value="{!!isset($chefs->id) ? $chefs->id : '0' !!}">
         {{-- <div class="type-switch-asw mr-2">
                <legend class="text-semibold">Type</legend>
                <label class="switch mb-0">
                    <input type="checkbox" name="type" class="typebtn" @if(isset($chefs->type) && $chefs->type == 'event') checked @endif>
                    <div class="slider round">
                        <span class="event">EVENT</span>
                        <span class="chef">CHEF</span>
                    </div>
                </label>
        </div> --}}
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Basic details</h5>
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
                            <div class="form-group">
                                <label class="text-semibold">Name</label>
                                <input type="text"  class="form-control" name="name" placeholder="Enter name" id="name" value="{!! isset($chefs->name) ? $chefs->name : old('name') !!}">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Business profile name </label>
                                <input type="text"  class="form-control" name="profile_name" placeholder="Enter Business profile name" id="profile_name" value="{!! isset($chefs->profile_name) ? $chefs->profile_name : old('profile_name') !!}">
                            </div>
                            <div class="form-group" id="mail_field">
                                <label class="text-semibold">Email</label>
                                <input type="email"  class="form-control" name="email" placeholder="Enter email" autocomplete="off" id="email" value="{!! isset($chefs->email) ? $chefs->email : old('email') !!}">
                            </div>
                            @if(\Auth::user()->role == 1 || \Auth::user()->role == 5)
                            <div class="form-group" id="individual_email">
                                <label class="text-semibold">Individual email</label>
                                <input type="email"  class="form-control" name="individual_email_1" placeholder="Enter individual email 1" autocomplete="off" value="{!!isset($chefs->individual_email_1) ? $chefs->individual_email_1 : old('individual_email_1') !!}">
                                <input type="email"  class="form-control" name="individual_email_2" id="individual_email2" placeholder="Enter individual email 2" autocomplete="off" value="{!!isset($chefs->individual_email_2) ? $chefs->individual_email_2 : old('individual_email_2') !!}">
                            </div>
                            @endif
                            <div class="form-group" id="mobile_field">
                                <input type="hidden"  class="form-control" name="location_id" placeholder="Enter phone code EX: 91"  id="location_id" value="91"> {{-- {!! isset($chefs->location_id) ? $chefs->location_id : old('location_id') !!} --}}
                                <label class="text-semibold">Mobile</label>
                                <input type="number" class="form-control" name="mobile" placeholder="Enter Mobile number" maxlength="10" id="mobile" value="{!!isset($chefs->mobile) ? $chefs->mobile : old('mobile') !!}" >
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Select City</label>
                                <select name="location" data-placeholder="Select city" class="select">
                                    <option value="" selected disabled>Select city</option>
                                    @if(count($city)>0)
                                    @foreach($city as $key=>$value)
                                    @if(isset($restaurant->location))
                                    <option @if($value->id == $restaurant->location || old('location')) selected @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @else
                                    <option value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" id="cuisines">
                                <label class="text-semibold">Cuisines</label>
                                <select name="cuisine_type[]" class="select-search" multiple="multiple" data-placeholder="Select cuisines">
                                    <option disabled hidden value> Select cuisines </option>
                                    @if(count($cuisines)>0)
                                    @foreach($cuisines as $key=>$value)
                                    @if(isset($chefs->cuisine_type))
                                    <option @if(in_array($value->id,explode(',',$chefs->cuisine_type)))  selected="" @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @else
                                    <option value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @if(!($chefpage))
                            <div class="form-group" id="aadhar_field">
                                <label class="text-semibold">Aadhar Image</label>
                                <div class="media no-margin-top align-items-stretch">
                                    @if(isset($userdocument[0]->aadar_image))
                                    @php
                                    $file       = explode('.', $userdocument[0]->aadar_image);
                                    $fileextn   = $file[1];
                                    @endphp
                                    <div class="media-left">
                                        @if($fileextn == 'jpg' || $fileextn == 'png' || $fileextn == 'jpeg')
                                        <a href="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->aadar_image) !!}"><img src="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->aadar_image) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                                        @else
                                        <a href="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->aadar_image) !!}" data-placement="left" data-toggle="tooltip" title="Click to update download Aadhar" download>
                                            <button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-download"></i></b></button>
                                            {{-- <img src="{{asset("assets/front/img/download-icon.jpg")}}" class="h-100"> --}}
                                        </a>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="aadar_image" id="aadar_image" accept="image/png, image/jpeg, image/jpg, application/pdf">
                                        <span class="help-block">Accepted formats: jpeg, png, jpg, pdf.{{--  Max file size 2Mb --}}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group @if(!($chefpage)) d-none @endif">
                                <label class="text-semibold">Avatar</label>
                                <div class="media no-margin-top">
                                    @if(isset($chefs->avatar))
                                    <div class="media-left">
                                        <a href="{!! url($chefs->avatar) !!}" download="{{ substr(strrchr($chefs->avatar,'/'),1) }}"><img src="{!! url($chefs->avatar) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                                    </div>
                                    @endif
                                    <div class="media-left" style="display: none;">
                                        <img src="" id="item-img-output" style="width: 58px; height: 58px; border-radius: 2px;" alt="Cropped Image">
                                    </div>
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="avatar" id="imageid" accept="image/png, image/jpeg">
                                        <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Other details</h5>
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
                            <div class="form-group" id="home_event_div">
                                <label class="display-block text-semibold">{{ '@Home Event' }}</label>
                                <label class="radio-inline">
                                    <input type="radio" name="homeevent" value="yes" class="styled" {{ isset($chefs->home_event) && ($chefs->home_event == 'yes') ? 'checked' : ''}} {{ isset($chefs->home_event) && ($chefs->home_event == 'no') ? 'disabled' : ''}}>
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="homeevent" value="no" class="styled" {{ (isset($chefs->home_event) && ($chefs->home_event == 'no') || !isset($chefs->home_event)) ? 'checked' : ''}} {{ isset($chefs->home_event) && ($chefs->home_event == 'yes') ? 'disabled' : ''}}>
                                    No (Normal Chef)
                                </label>
                            </div>
                            @if(\Auth::user()->role == 1 && (isset($chefs->type) && $chefs->type != 'event'))
                            @if($chefs->home_event == 'no')
                            <div class="form-group @if(!($chefpage)) d-none @endif" id="celebritydiv">
                                <label class="display-block text-semibold">Celebrity</label>
                                <label class="radio-inline">
                                    <input type="radio" name="celebrity" value="yes" class="styled" {!! isset($chefs->celebrity) && ($chefs->celebrity == 'yes') ? 'checked' : ''  !!} >
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="celebrity" value="no" class="styled" {!! isset($chefs->celebrity) && ($chefs->celebrity == 'no') ? 'checked' : ''  !!}>
                                    No (Normal Chef)
                                </label>
                            </div>
                            <div class="form-group @if(!($chefpage)) d-none @endif" id="promoteddiv">
                                <label class="display-block text-semibold">Promoted</label>
                                <label class="radio-inline">
                                    <input type="radio" name="promoted" value="yes" class="styled" {!! isset($chefs->promoted) && ($chefs->promoted=='yes') ? 'checked="checked"' : ''  !!} >
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="promoted" value="no" class="styled" {!! isset($chefs->promoted) && ($chefs->promoted=='no') ? 'checked' : ''  !!}>
                                    No
                                </label>
                            </div>
                            <div class="form-group @if(!($chefpage)) d-none @endif" id="certifiedDiv" @if(isset($chefs->celebrity) && $chefs->celebrity == 'yes') style="display:none;" @endif>
                                <label class="display-block text-semibold">Certified</label>
                                <label class="radio-inline">
                                    <input type="radio" name="certified" value="yes" class="styled" {!! isset($chefs->certified) && ($chefs->certified == 'yes') ? 'checked' : ''  !!} >
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="certified" value="no" class="styled" {!! isset($chefs->certified) && ($chefs->certified == 'no') ? 'checked' : ''  !!}>
                                    No
                                </label>
                            </div>
                            @endif
                            @endif
                            @if(isset($chefs->id) && $chefs->id != '' && $chefpage && (isset($chefs->type) && $chefs->type != 'event'))
                            <div class="form-group">
                                <label class="text-semibold">Note :</label>
                                Leave blank if you don't want to change current password
                            </div>
                            @endif
                            @if(empty($chefs) || (isset($chefs->type) && $chefs->type != 'event'))
                            <div class="form-group" id="password_field">
                                <label class="text-semibold">Password</label>
                                <input type="password"  class="form-control" name="password" placeholder="Enter password" id="password" value="">
                            </div>
                            <div class="form-group" id="retypepassword_field">
                                <label class="text-semibold">Retype password </label>
                                <input type="password"  class="form-control" name="confirm_password" placeholder="Enter confirm password" id="confirm_password" value="">
                            </div>
                            @endif
                            @if(!($chefpage) || (isset($chefs->type) && $chefs->type == 'event'))
                            @if(empty($chefs) || (isset($chefs) && $chefs->type != 'event'))
                            <div class="form-group" id="exp_field">
                                <label class="text-semibold">How long have you been selling your dishes professionally?</label>
                                <select name="time_to_sell" id="time_to_sell"  class="form-control">
                                     <option disabled value>Select time</option>
                                     <option value="0_2_Years" {{ isset($restaurant->time_to_sell) && ($restaurant->time_to_sell == '0_2_Years') ? 'selected' : '' }}>0-2 Years</option>
                                     <option value="2_5_Years" {{ isset($restaurant->time_to_sell) && ($restaurant->time_to_sell == '2_5_Years') ? 'selected' : '' }}>2-5 Years</option>
                                     <option value="More_than_5_Years" {{ isset($restaurant->time_to_sell) && ($restaurant->time_to_sell == 'More_than_5_Years') ? 'selected' : '' }}>More than 5 Years</option>
                                </select>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="text-semibold">Facebook</label>
                                <input type="text"  class="form-control" name="fa_link" placeholder="Enter your Facebook link"  id="fa_link" value="@if(isset($restaurant->facebook_link)) {!! $restaurant->facebook_link !!} @endif">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Instagram</label>
                                <input type="text"  class="form-control" name="in_link" placeholder="Enter your Instagram link"  id="in_link" value="@if(isset($restaurant->instagram_link)) {!! $restaurant->instagram_link !!} @endif">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Youtube</label>
                                <input type="text"  class="form-control" name="yo_link" placeholder="Enter your Youtube link"  id="yo_link" value="@if(isset($restaurant->youtube_link)) {!! $restaurant->youtube_link !!} @endif">
                            </div>
                            @if(empty($chefs) || (isset($chefs->type) && $chefs->type != 'event'))
                           <div class="form-group" id="fssai_field">
                                <label class="text-semibold">FSSAI</label>
                                <div class="media no-margin-top  align-items-stretch">
                                    @if(isset($userdocument[0]->fssai_certificate) && $userdocument[0]->fssai_certificate != '')
                                    @php
                                    $file       = explode('.', $userdocument[0]->fssai_certificate);
                                    $fileextn   = $file[1];
                                    @endphp
                                    <div class="media-left">
                                        @if($fileextn == 'jpg' || $fileextn == 'png' || $fileextn == 'jpeg')
                                        <a href="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->fssai_certificate) !!}"><img src="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->fssai_certificate) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                                        @else
                                        <a href="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->fssai_certificate) !!}" data-placement="left" data-toggle="tooltip" title="Click to download FSSAI certificate" download><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-download"></i></b></button></a>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="fssai_certificate" id="fssai_certificate" accept="image/png, image/jpeg, image/jpg, application/pdf">
                                        <span class="help-block">Accepted formats: jpeg, png, jpg, pdf.{{--  Max file size 2Mb --}}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </fieldset>
                    </div>
                </div>
            </div>
            @if(\Auth::user()->role == 1 || \Auth::user()->role == 5)
            <div class="col-md-12">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="text-semibold">Status</label>
                        <select name="status" id="status" class="select-search">
                            <option disabled hidden value="">select any one</option>
                            <option @if(isset($chefs->status) && $chefs->status=='pending') selected="" @endif value="pending">Pending</option>
                            <option @if(isset($chefs->status) && $chefs->status=='approved') selected="" @endif value="approved">Approved</option>
                            <!-- <option @if(isset($chefs->status) && $chefs->status=='suspended') selected="" @endif value="suspended">Suspended</option> -->
                            <option @if(isset($chefs->status) && $chefs->status=='cancelled') selected="" @endif value="cancelled">Rejected</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12" @if((isset($chefs->status) && $chefs->status != 'cancelled' && $chefs->status != 'suspended') || (!isset($chefs->status))) style="display:none;" @endif id="reason">
                <input type="text" name="reason" id="inputreason" value="{!! (isset($chefs->status) && ($chefs->status == 'cancelled' || $chefs->status == 'suspended')) ? $chefs->reason : '' !!}" class="form-control" placeholder="Enter the reason for reject">
            </div>
            @endif
            <div class="col-md-12">
                <div class="text-right">
                    <a href="@if($id == ''){!! url('admin/chef') !!}@elseif($chefpage){!! url('admin/chef') !!}@else{!! url('admin/chef/request') !!}@endif" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                    <button type="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /content area -->
@endsection
@section('script')

<script type="text/javascript">
    $(".styled").uniform({
        radioClass: 'choice'
    });
    $(document).on('change','input[name=homeevent]',function (e) {
        if (this.value == 'no') {
            $('#certifiedDiv').show();
            $('#celebritydiv').show();
            $('#promoteddiv').show();

        } else {
            $('#certifiedDiv').hide();
            $('#celebritydiv').hide();
            $('#promoteddiv').hide();
        }
    });
    $(document).on('change','input[name=celebrity]',function (e) {
        if (this.value == 'no') {
            $('#certifiedDiv').show();
        } else {
            $('#certifiedDiv').hide();
        }
    });

    $(document).on('change','.location',function(e){
        e.preventDefault();
        var location = $(this).val(); 
        var token = $("input[name='_token']").val();
        var url = base_url+"/admin/location_code";
        $.ajax({
            type : 'POST',
            url : url,
            data : {location:location,"_token": token},
            success : function(res){
                $('#code').val(res);
            },
            error : function(err){

            }
        });
    });

    $(document).on('change', '#status', function() {
        if ( this.value == 'cancelled') {
            $("#reason").show();
            $('#inputreason').attr('required',true);
        } else {
            $("#reason").hide();
            $('#inputreason').removeAttr('required',true);
        }
    });
     $(document).ready(function () {
      $("#mobile").keypress(function (e) {
         var length = $(this).val().length;
       if(length > 9) {
            return false;
       } else if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
       } else if((length == 0) && (e.which == 48)) {
            return false;
       }
      });
});
     $(document).ready(function(){
        var v_id = $('#c_id').val();
        if($('.typebtn').prop('checked') == true) {
            // $('#mail_field').hide();
            $('#individual_email').hide();
            $('#mobile_field').hide();
            $('#aadhar_field').hide();
            $('#password_field').hide();
            $('#retypepassword_field').hide();
            $('#exp_field').hide();
            $('#fssai_field').hide();
            $('#cuisines').hide();
            $('#email').val('');
            $('#password').val('');
            $('#mobile').val('');
            $('#chef_form').attr('novalidate',true);
            $('#home_event_div').hide();
            $('<input>').attr({
                type: 'hidden',
                name: 'type',
                id  : 'types',
                value: 'on'
            }).appendTo('#chef_form');
        }
        if(v_id != 0){  
            $('.typebtn').attr('disabled',true);
        }
     });

     $(document).on('change','.typebtn',function(){
        if($(this).prop('checked') == true) {
            // $('#mail_field').hide();
            $('#individual_email').hide();
            $('#mobile_field').hide();
            $('#aadhar_field').hide();
            $('#password_field').hide();
            $('#retypepassword_field').hide();
            $('#exp_field').hide();
            $('#fssai_field').hide();
            $('#cuisines').hide();
            $('#email').val('');
            $('#password').val('');
            $('#mobile').val('');
            $('#home_event_div').hide();
            $('#breadcrumb_activetext').text('Add new Event');
            $('<input>').attr({
                type: 'hidden',
                name: 'type',
                id : 'types',
                value: 'on'
            }).appendTo('#chef_form');
        } else if($(this).prop('checked') == false) {
            $('#mail_field').show();
            $('#individual_email').show();
            $('#mobile_field').show();
            $('#aadhar_field').show();
            $('#password_field').show();
            $('#retypepassword_field').show();
            $('#exp_field').show();
            $('#fssai_field').show();
            $('#breadcrumb_activetext').text('Add new Chef');
            $('#types').remove();
            $('#home_event_div').show(); 
        }
     });
</script>
@endsection