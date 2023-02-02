@extends('layouts.backend.app')
@section('page_header')
<?php
    $chef   = getUserData(\Request::segment(3));
    $cpage  = request()->has('page') ? request()->get('page') : '';
    $ipage  = request()->has('innerpage') ? request()->get('innerpage') : '';
    $from   = request()->has('from') ? request()->get('from') : '';
    $url    = '?from='.$from.'&page='.$cpage;
    $url2   = $url.'&innerpage='.$ipage;
?>
<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h5><span class="text-semibold">@if(getRoleName() == 'admin'){!! $chef->name !!}@else{!! 'Addons' !!}@endif</span> - {!! ucfirst($type) !!} edit</h5>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            @if(getRoleName() == 'admin')
            <li><a href="{!! url('admin/chef/'.$url) !!}">All chefs</a></li>
            @endif
            <li><a href="@if(getRoleName() == 'admin'){!! url('admin/chef/'.\Request::segment(3).'/addon'.$url2) !!}@else{!! url(getRoleName().'/common/addon'.$url2) !!}@endif">Manage {!! ucfirst($type) !!}s</a></li>
            <li class="active">Edit {!! ucfirst($type) !!}</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
<!-- Content area -->
<div class="content">
    <!-- Form horizontal -->
    <form action="{!!url(getRoleName().'/chef/'.$v_id.'/'.$type.'/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        <!-- {{ method_field('PUT') }} -->
        <input type="hidden" name="id" id="id" value="{!!isset($addon->id) ? $addon->id : ''!!}">
        <input type="hidden" name="type" id="type" value="{!!isset($type) ? $type : 'addon'!!}">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Create / edit {!!ucfirst($type)!!}</h5>
                        {{-- <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div> --}}
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="text-semibold">Name</label>
                                        <input type="text"  class="form-control" name="name" placeholder="Enter name"  id="name" value="{!!isset($addon->name) ? $addon->name : ''!!}" required="">
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label class="control-label col-lg-2">Content</label>
                                    <div class="col-lg-10">
                                        <input type="text"  class="form-control" name="content" placeholder="Enter content"  id="content" value="{!!isset($addon->content) ? $addon->content : ''!!}" required="">
                                    </div>
                                </div> --}}
                                <div class="col-md-2">
                                    @if($type == 'addon')
                                    <div class="form-group">
                                        <label class="text-semibold">Price</label>
                                        <input type="text"  class="form-control" name="price" placeholder="Enter addon price"  id="price" value="{!!isset($addon->price) ? $addon->price : ''!!}" required="">
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="text-semibold">Status</label>
                                        <select name="status" id="status" class="select-search" required="">
                                            <option value="">select any one</option>
                                            <option @if(isset($addon->status) && $addon->status=='active') selected="" @endif value="active">Active</option>
                                            <option @if(isset($addon->status) && $addon->status=='inactive') selected="" @endif value="inactive">In-Active</option>
                                            <option @if(isset($addon->status) && $addon->status=='declined') selected="" @endif value="declined">Declined</option>
                                            <option @if(isset($addon->status) && $addon->status=='p_inactive') selected="" @endif value="p_inactive">In-Active by partner</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12" @if((isset($addon->status) && $addon->status != 'declined') || (!isset($addon->status))) style="display:none;" @endif id="reason">
                                    <input type="text" name="reason" id="inputreason" value="{!! (isset($addon->status) && ($addon->status == 'declined')) ? $addon->reason : '' !!}" class="form-control" placeholder="Enter the reason for reject">
                                </div>
                            </div>
                        </fieldset>
                        <div class="text-right">
                            <a href="@if(getRoleName() == 'admin'){!! url('admin/chef/'.\Request::segment(3).'/addon'.$url) !!}@else{!! url(getRoleName().'/common/addon'.$url) !!}@endif" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                            <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /form horizontal -->
</div>
<!-- /content area -->
<script>
    $(document).on('change','#status',function(){
        if ( this.value == 'declined') {
            $('#reason').show();
            $('#inputreason').attr('required',true);
        }
        else {
            $('#reason').hide();
            $('#inputreason').removeAttr('required',true);
        }
    })
</script>    
@endsection
