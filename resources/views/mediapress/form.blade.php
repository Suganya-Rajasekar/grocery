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
            <h5><span class="text-semibold">Master - Blogs</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/mediapress'.$url)!!}">Mediapress</a></li>   
            <li class="active">@if(\Request::segment(3) == "create"){!! 'Create Blog' !!} @else {!! 'Edit Blog' !!} @endif</li>
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
    <form action="{!!url(getRoleName().'/mediapress/store')!!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Basic details</h5>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="c_id" id="c_id" value="{!!isset($blogs->id) ? $blogs->id : ''!!}">
                    <fieldset>
                        <div class="form-group">
                            <label>name</label>
                            <input type="text" name="c_name" class="form-control" id="c_name" placeholder="Enter name" value="{!!isset($blogs->name) ? $blogs->name : ''!!}" required="">
                        </div>
                        <div class="form-group">
                            <label class="text-semibold">Image</label>
                            <div class="media no-margin-top">
                                @if(isset($blogs->image))
                                <div class="media-left">
                                    <a href="{!! $blogs->image !!}" target="_blank"><img src="{!! $blogs->image !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                                </div>
                                <div class="media-left" style="display: none;">
                                    <img src="" id="item-img-output" style="width: 58px; height: 58px; border-radius: 2px;" alt="Cropped Image">
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
                            <select name="c_status" class="select-search" id="c_status" data-placeholder="select any one" required="">
                                <option value="">select any one</option>
                                <option value="active" @if(isset($blogs->status) && $blogs->status=='active') selected="" @endif>Active</option>
                                <option value="inactive" @if(isset($blogs->status) && $blogs->status=='inactive') selected="" @endif>In-Active</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Media details</h5>
                </div>
                <div class="panel-body">
                    <fieldset>
                        <div class="form-group mt-3">    
                            <label class="required">Mediapress type</label>
                            <label class="radio-inline">
                                <input type="radio" class="type" data-option="ext-link" name="type" value="external_link" required='' @if(isset($blogs->media_type) && $blogs->media_type == "external_link") checked @endif > External link
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="type" data-option="descript" name="type" value="description" required='' @if(isset($blogs->media_type) && $blogs->media_type == "description") checked @endif> Description
                            </label>
                        </div>
                        <div class="form-group media-link" @if(!isset($blogs) || $blogs->media_type != "external_link")style="display:none;" @endif>
                            <label>Mediapress link</label>
                            <input type="text" class="form-control" name="media_link" placeholder="Enter mediapress url" @if(isset($blogs) && $blogs->media_type == "external_link") value="{{ isset($blogs->description) ? $blogs->description : '' }}" @endif>
                        </div>   
                        <div class="form-group description" @if(!isset($blogs) || $blogs->media_type != "description") style="display:none;" @endif>
                            <label>Description</label>
                            <textarea name="description" rows="3" cols="3" class="form-control Messagearea" placeholder="Enter description" id="content">@if(isset($blogs) && $blogs->media_type == 'description') {!!isset($blogs->description) ? $blogs->description : ''!!} @endif</textarea>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>    
    <div class="text-right">
        <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
    </div>
    </form>
    <!-- /form horizontal -->
</div>
<!-- /content area -->
@endsection
@section('script')
<script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('content', {
   allowedContent:true,
}); 
</script>
<script type="text/javascript">
    $(document).on('click','.type',function(){
        var option = $(this).attr('data-option');
        if(option == "ext-link"){
            $('.description').hide();
            $('.media-link').show();
        } else {
            $('.media-link').hide();
            $('.description').show();
        }
    });
</script>
@endsection