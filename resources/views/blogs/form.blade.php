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
            <li><a href="{!!url(getRoleName().'/blogs'.$url)!!}">Blogs</a></li>
            <li class="active">{!! 'Edit Blog' !!}</li>
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
    <form action="{!!url(getRoleName().'/blogs/store')!!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
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
                                    <input type="file" class="file-styled" name="image" id="imageid1" accept="image/png, image/jpeg, image/jpg">
                                    <span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group description">
                            <label>Description</label>
                            <textarea name="description" rows="3" cols="3" class="form-control Messagearea" placeholder="Enter description" id="content">{!!isset($blogs->description) ? $blogs->description : ''!!}</textarea>
                        </div>
        
                        <div class="form-group">
                                <label class="text-semibold">Tags</label>
                                <select data-placeholder="Select Tags" name="tags[]" class="select-search" multiple=''>
                                    <option value="" hidden disabled>Select Tags</option>
                                    @if(count($tags)>0)
                                    @foreach($tags as $key=>$value)
                                    @if(isset($blogs->tags))
                                    <?php 
                                    $encode_tag=json_decode($blogs->tags);
                                    $tag_id = array_column($encode_tag, 'id');
                                    ?>
                                    <option @if(in_array($value->id,$tag_id))  selected @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @else
                                    <option value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                        </div>
                        <div class="form-group">
                                <label class="text-semibold">Category</label>
                                <select data-placeholder="Select Category" name="category[]" class="select-search" multiple=''>
                                    <option value="" hidden disabled>Select Category</option>
                                    @if(count($category)>0)
                                    @foreach($category as $key=>$value)
                                    @if(isset($blogs->category))
                                    <?php 
                                    $encode_cate=json_decode($blogs->category);
                                    $cate_id = array_column($encode_cate, 'id');
                                    ?>
                                    <option @if(in_array($value->id,$cate_id))  selected @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @else
                                    <option value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
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
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </div>
            </div>
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
@endsection