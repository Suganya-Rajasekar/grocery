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
            <h5><span class="text-semibold">Master - Edit Variant</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/unit'.$url)!!}">Variants</a></li>
            <li class="active">{!! 'Edit Variant' !!}</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')

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

        
        <form action="{!!url(getRoleName().'/unit/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        <!-- {{ method_field('PUT') }} -->
        <input type="hidden" name="id" id="id" value="{!!isset($addon->id) ? $addon->id : ''!!}">
        <input type="hidden" name="type" id="type" value="{!!isset($type) ? $type : 'addon'!!}">
        <fieldset class="content-group">
          <!-- <legend class="text-bold">Basic details</legend> -->

          <div class="form-group">
            <label class="control-label col-lg-2">Name</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="name" placeholder="Enter name"  id="name" value="{!!isset($addon->name) ? $addon->name : ''!!}" required="">
            </div>
          </div>

          <!-- <div class="form-group">
            <label class="control-label col-lg-2">Content</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="content" placeholder="Enter content"  id="content" value="{!!isset($addon->content) ? $addon->content : ''!!}" required="">
            </div>
          </div>  -->
          @if($type=='addon')
           <div class="form-group">
            <label class="control-label col-lg-2">Price</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="price" placeholder="Enter addon price"  id="price" value="{!!isset($addon->price) ? $addon->price : ''!!}" required="">
            </div>
          </div>
          @endif        

          <div class="form-group">
            <label class="control-label col-lg-2">Status</label>
            <div class="col-lg-10">
              <select name="status" id="status" class="select-search" required="">
                <option value="">select any one</option>
                <option @if(isset($addon->status) && $addon->status=='active') selected="" @endif value="active">Active</option>
                <option @if(isset($addon->status) && $addon->status=='inactive') selected="" @endif value="inactive">In-Active</option>
                <option @if(isset($addon->status) && $addon->status=='declined') selected="" @endif value="declined">Declined</option>
                <option @if(isset($addon->status) && $addon->status=='p_inactive') selected="" @endif value="p_inactive">In-Active by partner</option>
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
