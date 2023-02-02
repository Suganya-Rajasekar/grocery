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
            <h5><span class="text-semibold">Sub Admin - @if(!$subadmin) {!! 'Add Subadmin' !!} @else  {!! 'Edit Subadmin' !!} @endif</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/subadmin'.$url)!!}">Sub Admin</a></li>
            <li class="active">@if(!$subadmin) {!! 'Add Subadmin' !!} @else  {!! 'Edit Subadmin' !!} @endif</li>
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
        <form action="{!!url(getRoleName().'/subadmin/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name="c_id" id="c_id" value="{!!isset($subadmin->id) ? $subadmin->id : ''!!}">
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-flat subadmin">
        <div class="panel-heading">
          <h5 class="panel-title">Basic details</h5>
          <div class="heading-elements">
            <ul class="icons-list">
              <li><a data-action="collapse"></a></li>
            </ul>
          </div>
        </div>
        <div class="panel-body">
            <!-- <legend class="text-bold">Basic details</legend> -->

            <div class="form-group">
              <label class="text-semibold">Name</label>
              <input type="text"  class="form-control" name="name" placeholder="Enter name"  id="name" value="{!!isset($subadmin->name) ? $subadmin->name : ''!!}" required="">
            </div>

            <div class="form-group" style="margin-top: 44px;">
              <label class="text-semibold">Email</label>
              <input type="email"  class="form-control" name="email" placeholder="Enter email"  id="email" value="{!!isset($subadmin->email) ? $subadmin->email : ''!!}" required="">
            </div>

          {{-- <div class="form-group">
            <label class="control-label col-lg-2">Phone Code</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="phone_code" placeholder="Enter phone code EX: 91"  id="phone_code" value="{!!isset($subadmin->location_id) ? $subadmin->location_id : '' !!}" required="">
            </div>
          </div> --}}

          <div class="form-group">
            <label class="text-semibold">Mobile</label>
            <input type="text"  class="form-control" name="phone_number" placeholder="Enter Mobile number"  id="phone_number" value="{!!isset($subadmin->mobile) ? $subadmin->mobile : '' !!}" required="">
          </div>
        </div> 
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-flat subadmin">
        <div class="panel-heading">
          <h5 class="panel-title">Basic details</h5>
          <div class="heading-elements">
            <ul class="icons-list">
              <li><a data-action="collapse"></a></li>
            </ul>
          </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
              <label class="text-semibold">Avatar</label>
              <div class="media no-margin-top">
                @if(isset($subadmin->avatar))
                <div class="media-left">
                  <a href="{!! url($subadmin->avatar) !!}" download="{{ substr(strrchr($subadmin->avatar,'/'),1) }}"><img src="{!! url($subadmin->avatar) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                </div>
                      {{--  <div class="col-lg-4 imagediv">
                      <img src="{{url('/storage/app/public/avatar/'.$subadmin->avatar)}}" style="width: 40px;height: 40px;max-width: none;" class="img-circle" alt="">
                    </div> --}}
                    @endif
                    <div class="media-left" style="display: none;">
                      <img src="" id="item-img-output" style="width: 58px; height: 58px; border-radius: 2px;" alt="Cropped Image">
                    </div>
                    <div class="media-body text-nowrap">
                      <input type="file"  class="file-styled" name="avatar" id="imageid" accept="image/png, image/jpeg" >
                      <span class="help-block">Accepted formats: gif, png, jpg.</span>
                    </div> 
                  </div>
                </div>
                <div class="form-group">
                  <label class="text-semibold">Password</label>
                  <input type="text"  class="form-control" name="password" placeholder="Enter password"  id="password" value="" @if(isset($subadmin->id)=='') required="" @endif>
                </div>
                <div class="form-group">
                  <label class="text-semibold">Retype password </label>
                  <input type="text"  class="form-control" name="confirm_password" placeholder="Enter confirm password" id="confirm_password" value="" @if(isset($subadmin->id)=='') required="" @endif>
                </div>
        </div>  
      </div>
    </div>
    <div class="col-md-12">
      <div class="panel-body">
        <div class="form-group">
          <label class="text-semibold">Status</label>
          <select name="status" id="status" class="select-search" required="">
            <option value="">select any one</option>
            <option @if(isset($subadmin->status) && $subadmin->status=='pending') selected="" @endif value="pending">Pending</option>
            <option @if(isset($subadmin->status) && $subadmin->status=='approved') selected="" @endif value="approved">Approved</option>
            {{-- <option @if(isset($subadmin->status) && $subadmin->status=='suspended') selected="" @endif value="suspended">Suspended</option> --}}
            <option @if(isset($subadmin->status) && $subadmin->status=='cancelled') selected="" @endif value="cancelled">Rejected</option>
          </select>
        </div>
      </div>
    </div> 

    <div class="col-md-12">
      <div class="panel-body">  
       <table id="data-table" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Module</th>
            <!-- <th scope="col">Global</th> -->
            <th scope="col">View</th>
            <!-- <th>Create</th> -->
            <th>Edit</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
          @php 
      $aModules  = getModules(); /////tbl_modules
      $access    = modules_access_names(); /////global,create...etc
      $accessData= !empty($subadmin->access)?json_decode(json_encode($subadmin->access), true) : []; ///////tbl_modules_access
      @endphp
      @foreach($aModules as $key => $mods)
      @php $modId = $mods->id;
      $DBid = array_search($modId,array_column($accessData, 'id'));
      @endphp
      @if(count(getSideMenuSub($mods->id))==0)
      <tr>
        <th scope="row">{{$key+1
        }}</th>
        <td>{{$mods->menu_name}}</td>
        @foreach ($access as $k => $acc)
        <td><input 
          class="{{$acc}} {{$acc.'_'.$mods->id}} grp_{{$mods->id}}" 
          group-id="grp_{{$mods->id}}" 
          <?php if(!empty($accessData)) if($accessData[$DBid]['access'][$acc] == 1 && is_numeric($DBid)) { echo('checked'); } ?>
          type="checkbox" 
          name="check[{{$mods->id}}][{!!$acc!!}]"></td>
          @endforeach
        </tr>
        @endif
        @endforeach
      </tbody>
       </table>
    </div>
    </div>
  </div> 
    <!-- Submit Field -->
        <div class="text-right">
          <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
      </form>
  <!-- /form horizontal -->

</div>
<!-- /content area -->
@endsection
@section('script')
{{-- <script type="text/javascript">
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
        toast(message,'Error!', 'error');
        dimg.replaceWith( dimg = dimg.clone( true ) );
        dimg.val('');
    }
</script> --}}
@endsection

