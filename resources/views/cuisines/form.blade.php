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
            <h5><span class="text-semibold">Master - Edit Cuisine</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/common/cuisines'.$url)!!}">Cuisines</a></li>
            <li class="active">{!! 'Edit Cuisine' !!}</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
    <!-- Form horizontal -->
    <form action="{!!url(getRoleName().'/cuisines/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
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
                    <input type="hidden" name="c_id" id="c_id" value="{!!isset($cuisines->id) ? $cuisines->id : ''!!}">
                    <fieldset>
                        <div class="form-group">
                            <label class="text-semibold">Cuisine name</label>
                            <input type="text" name="c_name" class="form-control" id="c_name" placeholder="Enter Cuisine name" value="{!!isset($cuisines->name) ? $cuisines->name : ''!!}" required="">
                        </div>
                        <div class="form-group">
                            <label class="text-semibold">Image</label>
                            <div class="media no-margin-top">
                                @if(isset($cuisines->image))
                                <div class="media-left">
                                    <a href="{!! $cuisines->image !!}" target="_blank"><img src="{!! $cuisines->image !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                                </div>
                                @endif
                                <div class="media-body text-nowrap">
                                    <input type="file" class="file-styled" name="image" id="imageid" accept="image/png, image/jpeg, image/jpg">
                                    <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="5" cols="3" class="form-control limitcount" placeholder="Enter description">{!!isset($cuisines->description) ? $cuisines->description : ''!!}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="text-semibold">Status</label>
                            <select name="c_status" class="select-search" id="c_status" data-placeholder="select any one" required="">
                                <option value="">select any one</option>
                                <option value="active" @if(isset($cuisines->status) && $cuisines->status=='active') selected="" @endif>Active</option>
                                <option value="inactive" @if(isset($cuisines->status) && $cuisines->status=='inactive') selected="" @endif>In-Active</option>
                            </select>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <a href="{!! url(getRoleName().'/common/cuisines'.$url) !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
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

