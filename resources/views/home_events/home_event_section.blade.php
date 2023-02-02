@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h5><span class="text-semibold">Home Event Section</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li class="active">Edit Home Event Section</li>
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
      <h5 class="panel-title">Home event section details</h5>
      <div class="heading-elements">
        <ul class="icons-list">
          <li><a data-action="collapse"></a></li>
        </ul>
      </div>
    </div>

    <div class="panel-body">

        
        <form action="{!!url(getRoleName().'/home_event/section')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
         @method('PATCH')
        <input type="hidden" name="c_id" id="c_id" value="{!!isset($content->id) ? $content->id : ''!!}">
    <div class="container-fluid">
      <div class="row">
       <fieldset class="col-lg-6">  
        <div class="form-group">
          <label class="text-semibold">Meal section name</label>
            <input type="text"  class="form-control" name="meal_section" placeholder="Enter section 1" value="{{ $data->meal_section_name }}"required >
        </div>
        <div class="form-group">
          <label class="text-semibold">Preference section name</label>
            <input type="text"  class="form-control" name="preference_section" placeholder="Enter section 2" value="{{ $data->preference_section_name }}"required >
        </div>

      </fieldset>
      <fieldset class="col-lg-6">  
        <div class="form-group">
          <label class="text-semibold">Theme section name</label>
            <input type="text"  class="form-control" name="theme_section" placeholder="Enter section 3" value="{{ $data->theme_section_name }}"required >
        </div>
        <div class="form-group">
          <label class="text-semibold">Addon section name</label>
            <input type="text"  class="form-control" name="addon_section" placeholder="Enter addon section name" value="{{ $data->addon_section_name }}"required >
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