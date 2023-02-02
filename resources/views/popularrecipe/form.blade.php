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
            <h5><span class="text-semibold">Master - Popular recipe</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/popular_recipe'.$url)!!}">Popular recipe</a></li>
            <li class="active">{!! 'Edit popular recipe' !!}</li>
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
    <form action="{!!url(getRoleName().'/popular_recipe/store')!!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Basic details</h5>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="c_id" id="c_id" value="{!!isset($popular->id) ? $popular->id : ''!!}">
                    <fieldset>
                        <div class="form-group">
                            <label>name</label>
                            <input type="text" name="c_name" class="form-control" id="c_name" placeholder="Enter name" value="{!!isset($popular->name) ? $popular->name : ''!!}" required="">
                        </div>
                        <div class="form-group">
                            <label class="text-semibold">Image</label>
                            <div class="media no-margin-top">
                                @if(isset($popular->image))
                                <div class="media-left">
                                    <a href="{!! $popular->image !!}" target="_blank"><img src="{!! $popular->image !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
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
                        
                        <div class="form-group">
                            <label>Anyone Choose : </label>
                            <input type="radio" name="type" class="choose_video" value="desc" {!!(isset($popular->type) && $popular->type=='desc') ? 'checked' : '' !!} required> Description
                            <input type="radio" name="type" class="choose_video" value="video" {!!(isset($popular->video) && $popular->type=='video') ? 'checked' : '' !!} required> Video upload
                        </div>
                        <div class="form-group description" {!!(isset($popular->type) && $popular->type=='desc') ? '' : 'style="display: none;"' !!}>
                            <label>Description</label>
                            <textarea name="description" rows="3" cols="3" class="form-control" placeholder="Enter description">{!!isset($popular->description) ? $popular->description : ''!!}</textarea>
                        </div>

                        <div class="form-group video_upload" {!!(isset($popular->type) && $popular->type=='video') ? '' : 'style="display: none;"' !!}>
                            <label class="text-semibold">video upload</label>
                            <div class="media no-margin-top">
                                @if(isset($popular->video) && $popular->video!='')
                                <div class="media-left">
                                    <iframe src="{!! $popular->video !!}" name="test" height="120" width="120">You need a Frames Capable browser to view this content.</iframe>   
                                </div>
                                @endif
                                <div class="media-body text-nowrap">
                                    <input type="file" class="file-styled" name="file" id="file" accept="video/mp4,video/x-m4v,video/*">
                                </div>
                            </div>
                        </div>
                         
                        <div class="form-group">
                            <label class="text-semibold">Status</label>
                            <select name="c_status" class="select-search" id="c_status" data-placeholder="select any one" required="">
                                <option value="">select any one</option>
                                <option value="active" @if(isset($popular->status) && $popular->status=='active') selected="" @endif>Active</option>
                                <option value="inactive" @if(isset($popular->status) && $popular->status=='inactive') selected="" @endif>In-Active</option>
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

<script type="text/javascript">
    $(document).on("click", ".choose_video", function () {
     var val    = $(this).val();
     if(val=='desc'){
        $('.description').show();
        $('.video_upload').hide();
     } else {
        $('.description').hide();
        $('.video_upload').show();
     }
});
</script>
@endsection

