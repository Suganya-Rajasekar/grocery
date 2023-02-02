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
            <h5><span class="text-semibold">Pages - @if(!$pages) {!! 'Add Page' !!} @else  {!! 'Edit Page' !!} @endif</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/pages'.$url)!!}">Pages</a></li>
            <li class="active">@if(!$pages) {!! 'Add Page' !!} @else  {!! 'Edit Page' !!} @endif</li>
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

        
        <form action="{!!url(getRoleName().'/pages/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        <!-- {{ method_field('PUT') }} -->
        <input type="hidden" name="c_id" id="c_id" value="{!!isset($pages->id) ? $pages->id : ''!!}">
        <fieldset class="content-group">
          <!-- <legend class="text-bold">Basic details</legend> -->

          <div class="form-group">
            <label class="control-label col-lg-2">Title</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="title" placeholder="Enter title"  id="title" value="{!!isset($pages->title) ? $pages->title : ''!!}" required="">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Slug</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="slug" placeholder="Enter slug"  id="slug" value="{!!isset($pages->slug) ? $pages->slug : ''!!}" required="">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Content</label>
            <div class="col-lg-10">
              <textarea name="content" id="content" class="form-control Messagearea" required="">{!!isset($pages->content) ? $pages->content : ''!!}</textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-lg-2">Status</label>
            <div class="col-lg-10">
              <select name="status" id="status" class="select-search" required="">
                <option value="">select any one</option>
                <option @if(isset($pages->status) && $pages->status=='active') selected="" @endif value="active">Active</option>
                <option @if(isset($pages->status) && $pages->status=='inactive') selected="" @endif value="inactive">In-Active</option>
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
@section('script')
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/26.0.0/classic/ckeditor.js"></script> --}}
<script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace('content', {
     allowedContent:true,
  });
</script>
<script type="text/javascript">
  "use strict"; 
 /* ClassicEditor.create( document.querySelector( '#content' ) )
                        .then( editor => {
                           editor.config.allowedContent = true;
                        } )
                        .catch( error => {
                        } );*/
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

