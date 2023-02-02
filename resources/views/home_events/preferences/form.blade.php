@extends('layouts.backend.app')
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
            <h5><span class="text-semibold">Home Event - @if(isset($preference)) Edit @else Create @endif preference</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!! Cache::has('preference_backurl') ? cache::get('preference_backurl') : url(getRoleName().'/home_event/preferences')!!}">preferences</a></li>
            <li class="active">@if(isset($preference)) {!! 'Edit preference' !!} @else {!! 'Create preference' !!} @endif </li>
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

        
        <form action="{!!url(getRoleName().'/home_event/preferences')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="{!!isset($preference->id) ? $preference->id : ''!!}">
        <fieldset class="content-group">

          <div class="form-group">
            <label class="text-semibold">Name</label>
              <input type="text"  class="form-control" name="name" placeholder="Enter preference name"  id="name" value="{!!isset($preference->name) ? $preference->name : ''!!}" required="">
          </div> 

          <div class="form-group">
            <label class="text-semibold">Status</label>
              <select name="status" id="status" class="select-search" required="">
                <option value="">select any one</option>
                <option @if(isset($preference->status) && $preference->status=='active') selected="" @endif value="active">Active</option>
                <option @if(isset($preference->status) && $preference->status=='inactive') selected="" @endif value="inactive">In-Active</option>
              </select>
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
