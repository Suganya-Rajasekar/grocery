@extends('layouts.backend.app')
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content">
    <!-- Form horizontal -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">@if($id == ''){!! 'Add new Chef' !!}@elseif($chefpage){!! 'Edit chef' !!}@else{!! 'Chef request approval' !!}@endif</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <form action="{!!url(getRoleName().'/chef/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
                {{ csrf_field() }}{{ method_field('PUT') }}
                <input type="hidden" name="c_id" id="c_id" value="{!!isset($chefs->id) ? $chefs->id : ''!!}">
                <fieldset class="content-group">
                    <legend class="text-bold">Basic details</legend>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Name</label>
                        <div class="col-lg-10">
                            <input type="text"  class="form-control" name="name" placeholder="Enter name"  id="name" value="{!!isset($chefs->name) ? $chefs->name : old('name') !!}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Email</label>
                        <div class="col-lg-10">
                            <input type="email"  class="form-control" name="email" placeholder="Enter email"  id="email" value="{!!isset($chefs->email) ? $chefs->email : old('email') !!}">
                        </div>
                    </div>
                    <div class="form-group @if(!($chefpage)) d-none @endif">
                        <label class="control-label col-lg-2">Phone Code</label>
                        <div class="col-lg-10">
                            <input type="text"  class="form-control" name="location_id" placeholder="Enter phone code EX: 91"  id="location_id" value="{!!isset($chefs->location_id) ? $chefs->location_id : old('location_id') !!}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Mobile</label>
                        <div class="col-lg-10">
                            <input type="text"  class="form-control" name="mobile" placeholder="Enter Mobile number"  id="mobile" value="{!!isset($chefs->mobile) ? $chefs->mobile : old('mobile') !!}" >
                        </div>
                    </div>
                    <div class="form-group @if(!($chefpage)) d-none @endif">
                        <label class="control-label col-lg-2">Avatar</label>
                        <div class="col-lg-6">
                            <input type="file"  class="form-control" name="avatar" id="imageid" accept="image/png, image/jpeg" >
                            {{-- <small>Size (512 * 512)</small> --}}
                        </div>
                        @if(isset($chefs->avatar))
                        <div class="col-lg-4">
                            <img src="{{url($chefs->avatar)}}" class="img-circle" alt="">
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Cuisine</label>
                        <div class="col-lg-10">
                            <select name="cuisine_type[]" class="select-search" multiple="">
                                <option disabled hidden value=""> Choose cuisines </option>
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
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Select City</label>
                        <div class="col-lg-10">
                            <select name="location" class="select-search location" required >
                                <option value="" selected disabled>Select city</option>
                                @if(count($city)>0)
                                @foreach($city as $key=>$value)
                                @if(isset($restaurant->location))
                                <option @if($value->id==$restaurant->location)  selected="" @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                @else
                                <option value="{!!$value->id!!}">{!!$value->name!!}</option>
                                @endif
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group d-none">
                        <label class="control-label col-lg-2">Location code</label>
                        <div class="col-lg-10">
                            <input type="text"  class="form-control" name="code" id="code" value="" disabled="">
                        </div>
                    </div>
                    @if(isset($chefs->id) && $chefs->id != '' && $chefpage)
                    <div class="form-group">
                        <label class="control-label col-lg-2"></label>
                        <div class="col-lg-10">
                            <b>Note : </b> Leave blank if you don't want to change current password
                        </div>
                    </div>
                    @endif
                    <div class="form-group @if(!($chefpage)) d-none @endif">
                        <label class="control-label col-lg-2">Password</label>
                        <div class="col-lg-10">
                            <input type="text"  class="form-control" name="password" placeholder="Enter password" id="password" value="">
                        </div>
                    </div>
                    <div class="form-group @if(!($chefpage)) d-none @endif">
                        <label class="control-label col-lg-2">Retype password </label>
                        <div class="col-lg-10">
                            <input type="text"  class="form-control" name="confirm_password" placeholder="Enter confirm password" id="confirm_password" value="">
                        </div>
                    </div>
                    @if(\Auth::user()->role == 1)
                    <div class="form-group @if(!($chefpage)) d-none @endif">
                        <label class="control-label col-lg-2">Celebrity</label>
                        <div class="col-lg-10">
                            <label class="radio-inline">
                                <input type="radio" name="celebrity" value="yes" class="styled" {!! isset($chefs->celebrity) && ($chefs->celebrity == 'yes') ? 'checked' : ''  !!} >
                                Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="celebrity" value="no" class="styled" {!! isset($chefs->celebrity) && ($chefs->celebrity == 'no') ? 'checked' : ''  !!}>
                                No (Normal Chef)
                            </label>
                        </div>
                    </div>
                    <div class="form-group @if(!($chefpage)) d-none @endif">
                        <label class="control-label col-lg-2">Promoted</label>
                        <div class="col-lg-10">
                            <label class="radio-inline">
                                <input type="radio" name="promoted" value="yes" class="styled" {!! isset($chefs->promoted) && ($chefs->promoted=='yes') ? 'checked="checked"' : ''  !!} >
                                Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="promoted" value="no" class="styled" {!! isset($chefs->promoted) && ($chefs->promoted=='no') ? 'checked' : ''  !!}>
                                No
                            </label>
                        </div>
                    </div>
                    <div class="form-group @if(!($chefpage)) d-none @endif" id="certifiedDiv" @if(isset($chefs->celebrity) && $chefs->celebrity == 'yes') style="display:none;" @endif>
                        <label class="control-label col-lg-2">Certified</label>
                        <div class="col-lg-10">
                            <label class="radio-inline">
                                <input type="radio" name="certified" value="yes" class="styled" {!! isset($chefs->certified) && ($chefs->certified == 'yes') ? 'checked' : ''  !!} >
                                Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="certified" value="no" class="styled" {!! isset($chefs->certified) && ($chefs->certified == 'no') ? 'checked' : ''  !!}>
                                No
                            </label>
                        </div>
                    </div>
                    @endif
                    @if(!($chefpage))
                    <div class="form-group">
                        <label class="control-label col-lg-2">Aadhar Card</label>
                        <div class="col-lg-6">
                            <input type="file"  class="form-control" name="aadar_image" id="aadar_image">
                        </div>
                        @if(isset($userdocument[0]->aadar_image))
                        @php
                            $file       = explode('.', $userdocument[0]->aadar_image);
                            $fileextn   = $file[1];
                        @endphp
                        <div class="col-lg-4">
                            @if($fileextn == 'jpg' || $fileextn == 'png' || $fileextn == 'jpeg')
                                <a href="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->aadar_image) !!}"><img src="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->aadar_image) !!}" class="img-circle"></a>
                            @else
                                <a href="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->aadar_image) !!}" data-placement="left" data-toggle="tooltip" title="Click to update business profile" download><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-download"></i></b></button></a>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">FSSAI</label>
                        <div class="col-lg-6">
                            <input type="file"  class="form-control" name="fssai_certificate" id="fssai_certificate">
                        </div>
                        @if(isset($userdocument[0]->fssai_certificate))
                        @php
                            $file       = explode('.', $userdocument[0]->fssai_certificate);
                            $fileextn   = $file[1];
                        @endphp
                        <div class="col-lg-4">
                            @if($fileextn == 'jpg' || $fileextn == 'png' || $fileextn == 'jpeg')
                                <a href="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->fssai_certificate) !!}"><img src="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->fssai_certificate) !!}" class="img-circle"></a>
                            @else
                                <a href="{!! url('uploads/user_document/'.$id.'/'.$userdocument[0]->fssai_certificate) !!}" data-placement="left" data-toggle="tooltip" title="Click to update business profile" download><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-download"></i></b></button></a>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="control-label col-lg-2">Status</label>
                        <div class="col-lg-10">
                            <select name="status" id="status" class="select-search">
                                <option disabled hidden value="">select any one</option>
                                <option @if(isset($chefs->status) && $chefs->status=='pending') selected="" @endif value="pending">Pending</option>
                                <option @if(isset($chefs->status) && $chefs->status=='approved') selected="" @endif value="approved">Approved</option>
                                <option @if(isset($chefs->status) && $chefs->status=='suspended') selected="" @endif value="suspended">Suspended</option>
                                <option @if(isset($chefs->status) && $chefs->status=='cancelled') selected="" @endif value="cancelled">Cancelled</option>
                            </select>
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
<style type="text/css">
    .img-circle {
        width: 40px;height: 40px;max-width: none;border-radius: 50%;
    }
</style>
@section('script')
<script type="text/javascript">
    $(".styled").uniform({
        radioClass: 'choice'
    });
    $(document).on('change','input[name=celebrity]',function (e) {
        if (this.value == 'no') {
            $('#certifiedDiv').show();
        } else {
            $('#certifiedDiv').hide();
        }
    })

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
                // if(width == 512 && height == 512){
                    img.id = 'bimage';
                    img.style = "width: 40px;height: 40px;max-width: none;";
                    $(".imagediv").html(img);
                // } else {
                //     var message = 'Image Size should be 512*512 pixels.';
                //     clearimage(message);
                // }
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
        alert(message);
        dimg.replaceWith( dimg = dimg.clone( true ) );
        dimg.val('');
    }
</script>
@endsection