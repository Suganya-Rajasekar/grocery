@extends('layouts.backend.app')
@section('content')

<!-- Content area -->
<div class="content">

  <!-- Form horizontal -->
  <div class="panel panel-flat">
    <div class="panel-heading">
      <h5 class="panel-title">Create/Edit Category</h5>
      <div class="heading-elements">
        <ul class="icons-list">
          <li><a data-action="collapse"></a></li>
        </ul>
      </div>
    </div>

    <div class="panel-body">

        
        <form action="{!!url('chef/'.$v_id.'/category/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        <!-- {{ method_field('PUT') }} -->
        <input type="hidden" name="id" id="id" value="{!!isset($category->id) ? $category->id : ''!!}">
        <fieldset class="content-group">
          <legend class="text-bold">Basic details</legend>

          <div class="form-group">
            <label class="control-label col-lg-2">Name</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="name" placeholder="Enter name"  id="name" value="{!!isset($category->name) ? $category->name : ''!!}" required="">
            </div>
          </div>

          <!-- <div class="form-group">
            <label class="control-label col-lg-2">Content</label>
            <div class="col-lg-10">
              <input type="text"  class="form-control" name="content" placeholder="Enter content"  id="content" value="{!!isset($category->content) ? $category->content : ''!!}" required="">
            </div>
          </div> -->       

          <div class="form-group">
            <label class="control-label col-lg-2">Status</label>
            <div class="col-lg-10">
              <select name="status" id="status" class="select-search" required="">
                <option value="">select any one</option>
                <option @if(isset($category->status) && $category->status=='active') selected="" @endif value="active">Active</option>
                <option @if(isset($category->status) && $category->status=='inactive') selected="" @endif value="inactive">In-Active</option>
                <option @if(isset($category->status) && $category->status=='declined') selected="" @endif value="declined">Declined</option>
                <option @if(isset($category->status) && $category->status=='p_inactive') selected="" @endif value="p_inactive">In-Active by partner</option>
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
