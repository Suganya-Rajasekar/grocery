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
            <h5><span class="text-semibold">Home content</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li class="active">Edit Home content</li>
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

        
        <form action="{!!url(getRoleName().'/homecontent_save')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        <!-- {{ method_field('PUT') }} -->
        <input type="hidden" name="c_id" id="c_id" value="{!!isset($content->id) ? $content->id : ''!!}">
    <div class="container-fluid">
      <div class="row">
       <fieldset class="content-group col-lg-6">
          <!-- <legend class="text-bold">Basic details</legend> -->
                      <div class="form-group">
                        <label class="control-label col-lg-6">Main banner title</label>
                        <div class="col-lg-10">
                          <input type="text"  class="form-control" name="title" placeholder="Enter name"  id="name" value="{!!isset($content->title) ? $content->title : ''!!}" required="">
                        </div>
                      </div>

                  <div class="form-group">
                    <label class="control-label col-lg-6">Main banner subtitle</label>
                    <div class="col-lg-10">
                      <input type="text"  class="form-control" name="subtitle" placeholder="Enter name"  id="name" value="{!!isset($content->subtitle) ? $content->subtitle : ''!!}" required="">
                    </div>
                  </div>
                <div class="form-group">
                  <label class="control-label col-lg-6">Main banner image</label>
                  <div class="col-lg-10">
                   <div class="media no-margin-top">
                     @if(isset($content->banimg))
                      <div class="media-left">
                        <input type="hidden"  class="form-control" name="hidden_img" value="{!!isset($content->banimg) ? $content->banimg : ''!!}">
                        <a href="{{url('/storage/app/public/homecontent/'.$content->banimg)}}"><img src="{{url('/storage/app/public/homecontent/'.$content->banimg)}}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                      </div>
                     @endif
                      <div class="media-body text-nowrap">
                        <input type="file" class="file-styled" name="banimg" accept="image/png, image/jpeg">
                        <span class="help-block">Accepted formats: png, jpg.</span>
                      </div>
                   </div> 
                  </div>
       </fieldset>
       <fieldset class="col-lg-6">  
                <div class="form-group">
                  <label class="control-label col-lg-2">Section 1</label>
                  <div class="col-lg-10">
                    <input type="text"  class="form-control" name="section1" placeholder="Enter section 1" value="{{ $content->section1 }}"required >
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-lg-2">Section 2</label>
                  <div class="col-lg-10">
                    <input type="text"  class="form-control" name="section2" placeholder="Enter section 2" value="{{ $content->section2 }}"required >
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-lg-2">Section 3</label>
                  <div class="col-lg-10">
                    <input type="text"  class="form-control" name="section3" placeholder="Enter section 3" value="{{ $content->section3 }}"required >
                  </div>
                </div>
       </fieldset>
    </div> 
    </div>

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
/*var dimg = $("#imageid");
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
        alert(message);
        dimg.replaceWith( dimg = dimg.clone( true ) );
        dimg.val('');
    }*/
</script>
@endsection

