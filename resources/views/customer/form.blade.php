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
        <div class="page-title">
            <h5><span class="text-semibold">Customers - @if(!$customer) {!! 'Add Customer' !!} @else  {!! 'Edit Customer' !!} @endif</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/customer/all'.$url)!!}">All Customer</a></li>
            <li class="active">@if(!$customer) {!! 'Add Customer' !!} @else  {!! 'Edit Customer' !!} @endif</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')

<!-- Content area -->
<div class="content">

  <!-- Form horizontal -->
  <div class="panel panel-flat">
    <div class="panel-heading">
      <h5 class="panel-title">Basic details</h5>
      <div class="heading-elements">
        <ul class="icons-list">
          <li><a data-action="collapse"></a></li>
        </ul>
      </div>
    </div>

    <div class="panel-body">

        
        <form action="{!!url(getRoleName().'/customer/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        <!-- {{ method_field('PUT') }} -->
        <input type="hidden" name="c_id" id="c_id" value="{!!isset($customer->id) ? $customer->id : ''!!}">
        <fieldset class="content-group">
          <!-- <legend class="text-bold">Basic details</legend> -->

          <div class="form-group">
            <label class="control-label col-lg-2">Name</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="name" placeholder="Enter name"  id="name" value="{!!isset($customer->name) ? $customer->name : ''!!}" required="">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Email</label>
            <div class="col-lg-10">
              <input type="email"  class="form-control" name="email" placeholder="Enter email"  id="email" value="{!!isset($customer->email) ? $customer->email : ''!!}" required="">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Phone Code</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="phone_code" placeholder="Enter phone code EX: 91"  id="phone_code" value="{!!isset($customer->location_id) ? $customer->location_id : '' !!}" required="">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Mobile</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="phone_number" placeholder="Enter Mobile number"  id="phone_number" value="{!!isset($customer->mobile) ? $customer->mobile : '' !!}" required="">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Avatar</label>
            <div class="col-lg-6">
              <input type="file"  class="form-control" name="avatar" id="imageid" accept="image/png, image/jpeg" >
               <small>Size (512 * 512)</small>
            </div>
            @if(isset($customer->avatar))
            <div class="col-lg-4 imagediv">
             <img src="{{url('/storage/app/public/avatar/'.$customer->avatar)}}" style="width: 40px;height: 40px;max-width: none;" class="img-circle" alt="">
            </div>
            @endif
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Status</label>
            <div class="col-lg-10">
              <select name="status" id="status" class="select-search" required="">
                <option value="">select any one</option>
                <option @if(isset($customer->status) && $customer->status=='pending') selected="" @endif value="pending">Pending</option>
                <option @if(isset($customer->status) && $customer->status=='approved') selected="" @endif value="approved">Approved</option>
                <option @if(isset($customer->status) && $customer->status=='suspended') selected="" @endif value="suspended">Suspended</option>
                <option @if(isset($customer->status) && $customer->status=='cancelled') selected="" @endif value="cancelled">Cancelled</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Password</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="password" placeholder="Enter password"  id="password" value="" @if(isset($customer->id)=='') required="" @endif>
            </div>
          </div>
          <div class="form-group">
                        <label class="control-label col-lg-2">Retype password </label>
                        <div class="col-lg-10">
                            <input type="text"  class="form-control" name="confirm_password" placeholder="Enter confirm password" id="confirm_password" value="" @if(isset($customer->id)=='') required="" @endif>
                        </div>
                    </div>

        </fieldset>

        <div class="text-right">
          <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
      </form>
    </div>
  </div>
  <!-- /form horizontal -->

</div>
<!-- /content area -->
@endsection
@section('script')
<script type="text/javascript">
  "use strict"; 
var dimg = $("#imageid");
    var _URL = window.URL || window.webkitURL;
    $("#imageid").change(function(e) {
        var file, img;
        if (file = $(this).get(0).files[0]) {
            img = new Image();
            img.src = window.URL.createObjectURL( file );
            img.onload = function() {
                var width = img.naturalWidth,
                height = img.naturalHeight;
                if(width == 512 && height == 512){
                    img.id = 'bimage';
                    img.style = "width: 40px;height: 40px;max-width: none;";
                    $(".imagediv").html(img);
                } else {
                    var message = 'Image Size should be 512*512 pixels.';
                    clearimage(message);
                }
            };
            img.onerror = function() {
                var message = 'Kindly upload valid image';
                clearimage(message);
            };
        }
    });
    function clearimage(message='')
    {
        if(message != '')
        //Sweet('error',message);
        toast(message, 'Error!', 'error');
        dimg.replaceWith( dimg = dimg.clone( true ) );
        dimg.val('');
    }
</script>
@endsection

