@extends('layouts.backend.app')
@section('page_header')
<?php
    $cpage  = request()->has('page') ? request()->get('page') : '';
    $status = request()->has('status') ? request()->get('status') : '';
    $search = request()->has('search') ? request()->get('search') : '';
    $from   = request()->has('from') ? request()->get('from') : '';
    $url    = '?from='.$from.'&page='.$cpage.'&status='.$status.'&search='.$search;
?>
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h5><span class="text-semibold">Master - Edit Explore</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/common/explore'.$url)!!}">Explore</a></li>
            <li class="active">{!! 'Edit Explore' !!}</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
    <!-- Form horizontal -->
    <form action="{!!url(getRoleName().'/explore/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name="page" value="{!! $cpage !!}">
        <input type="hidden" name="status" value="{!! $status !!}">
        <input type="hidden" name="search" value="{!! $search !!}">
        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Basic details</h5>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="id" id="c_id" value="{!!isset($explore->id) ? $explore->id : ''!!}">
                    <fieldset>

                        @if(count($exploreall)>0)
                        @foreach($exploreall as $key=>$value)

                        <?php $used_tags[] = $value->slug;?> 
                        @endforeach
                        @endif
                        <div class="form-group">
                            <label class="text-semibold">Explore name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter explore name" value="{!!isset($explore->name) ? $explore->name : ''!!}" required="">
                        </div>
                      
                        <div class="form-group">
                            <label class="text-semibold">Select Tag</label>
                            <select name="slug" id="tags" class="form-control" required="">
                            <option value="">Select Category</option>
                            @if(count($tags)>0)

                            @foreach($tags as $key=>$value)

                            <option value="{!!$value->id!!}" <?php if(!empty($explore) && $explore->slug == $value->id){ echo 'selected'; } ?> @if(isset($used_tags) && $used_tags != '' && in_array($value->id,$used_tags)) disabled @endif>{!!$value->name!!}</option> 

                            @endforeach
                            @endif 
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="text-semibold">Image</label>
                            <div class="media no-margin-top">
                                @if(isset($explore->image))
                                <div class="media-left">
                                    <a href="{!! $explore->image !!}" target="_blank"><img src="{!! $explore->image !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                                </div>
                                @endif
                                <div class="media-body text-nowrap">
                                    <input type="file" class="file-styled" name="image" id="imageid" accept="image/png, image/jpeg, image/jpg">
                                    <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="text-semibold">Status</label>
                            <select name="status" id="status" class="form-control" required="">
                            <option value="">select any one</option>
                            <option value="active" @if(isset($explore->status) && $explore->status=='active') selected="" @endif>Active</option>
                            <option value="inactive" @if(isset($explore->status) && $explore->status=='inactive') selected="" @endif>In-Active</option>
                            </select>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <a href="{!! url(getRoleName().'/common/explore'.$url) !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /form horizontal -->
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

