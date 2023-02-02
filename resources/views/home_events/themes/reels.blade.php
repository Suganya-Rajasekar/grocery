@extends('layouts.backend.app')
<style>
 .theme_img_remove  {
  font-size: 12px;
  position: absolute;
  left: 35px;
  width: 18px !important;
  height: 18px;
  display: flex;
  justify-content: center;
  align-content: center;
  top: -11px;
  background: red;
  color: white;
  border-radius: 50%;
  padding: 3px;
  cursor: pointer;
}
</style>
@section('page_header')
{{-- <?php
    $cpage  = request()->has('page') ? request()->get('page') : '';
    $from   = request()->has('from') ? request()->get('from') : '';
    $url    = '?from='.$from.'&page='.$cpage;
?> --}}
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h5><span class="text-semibold">Home Event - @if(isset($theme)) Edit @else Create @endif Theme</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!! Cache::has('theme_backurl') ? cache::get('theme_backurl') : url(getRoleName().'/home_event/themes')!!}">Themes</a></li>
            <li class="active">@if(isset($theme)) {!! 'Edit Theme' !!} @else {!! 'Create Theme' !!} @endif </li>
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

        
        <form action="{!!url(getRoleName().'/reels/save')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="{!!isset($theme->id) ? $theme->id : ''!!}">
        <div class="container-fluid">
        <div class="row">
        <fieldset class="col-lg-6"> 
         <div class="form-group">
            <label class="text-semibold">Title</label>
            <input type="text"  class="form-control" name="name" placeholder="Enter theme name"  id="name" value="" required="">
         </div>
         <div class="form-group">
            <label class="text-semibold">Description</label>
            <input type="text"  class="form-control" name="name" placeholder="Enter theme name"  id="name" value="" required="">
         </div>
         <div class="form-group ">
            <label class="text-semibold" >Reel video</label>
            <div class="media no-margin-top">
              <div class="media-body text-nowrap">
                <input type="file"  class="file-styled theme_img1" name="reels" accept="image/png, image/jpeg, image/jpg" multiple >
                <span class="help-block">Accepted formats: jpeg, png, jpg. Max file size 2Mb.</span>
                <span class="help-block">Only allow 4 images</span>
              </div>
            </div>
          </div>
          {{-- <div class="form-group">
            <input type="file" name="reels" class="form-control">
          </div> --}}
        </fieldset>
        <fieldset class="col-lg-6"> 
          <div class="form-group">
            <label class="text-semibold">Validity date time</label>
            <input type="text"  class="form-control" name="name" placeholder="Enter theme name"  id="name" value="" required="">
          </div>
          <div class="form-group">
            <label class="text-semibold">Status</label>
            <input type="text"  class="form-control" name="name" placeholder="Enter theme name"  id="name" value="" required="">
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
<script type="text/javascript">
  $(document).on('click','.theme_img_remove',function(){
    var theme_id = $('#id').val();
    var img_id   = $(this).attr('data-img-id');
    var img_no   = $(this).attr('data-img-key');
    $.ajax({
        url:base_url+'/admin/home_event/themes/img_remove',
        type: "DELETE",
        data: {id : theme_id,image_id : img_id},
        success:function(res){
          $('#image_div_'+img_no).remove();
        } 
    })
  });
</script>
@endsection
