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
			<h5>
				<span class="text-semibold">Banners - @if(!$banner) {!! 'Add Banner' !!} @else  {!! 'Edit Banner' !!} @endif
				</span>
			</h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li><a href="{!!url(getRoleName().'/banner'.$url)!!}">Banners</a></li>
			<li class="active">@if(!$banner) {!! 'Add Banner' !!} @else  {!! 'Edit Banner' !!} @endif</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
<!-- Content area -->
<div class="content">
	<!-- Form horizontal -->
	<form action="{!!url(getRoleName().'/banner/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input type="hidden" name="id" id="id" value="{!! isset($banner->id) ? $banner->id : '' !!}">
		<div class="row">
			<div class="col-md-6">
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
						<fieldset>
							<div class="form-group">
								<label class="text-semibold">Image</label>
								<div class="media no-margin-top align-items-stretch">
									@if(isset($banner->image) && !empty($banner->image))
									@php
									$file       = explode('.', $banner->image);
									$fileextn   = $file[1];
									@endphp
									<div class="media-left">
										@if($fileextn == 'jpg' || $fileextn == 'png' || $fileextn == 'jpeg')
										<a href="{!! $banner->image_src !!}"><img src="{!! $banner->image_src !!}" style="height: 58px; border-radius: 2px;" alt=""></a>
										@else
										<a href="{!! $banner->image_src !!}" data-placement="left" data-toggle="tooltip" title="Click to update download Aadhar" download>
											<button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-download"></i></b></button>
										</a>
										@endif
									</div>
									@endif
									<div class="media-body text-nowrap">
										<input type="file" class="file-styled" name="image" id="aadar_image" accept="image/png, image/jpeg, image/jpg, application/pdf">
										<span class="help-block">Accepted formats: jpeg, png, jpg. {{--  Max file size 2Mb --}}</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="text-semibold">Date</label>
								<input type="text" class="form-control daterange-basic1" name="date" value="{!! isset($banner->start_date) && ($banner->start_date!='' && $banner->end_date!='') ? date('Y-m-d',strtotime($banner->start_date)).' - '.date('Y-m-d',strtotime($banner->end_date)) : '' !!}"> 
							</div>
							<div class="form-group">
								<label class="text-semibold">Status</label>
								<select name="status" id="status" class="select-search" required="">
									<option value="">select any one</option>
									<option @if(isset($banner->status) && $banner->status=='active') selected="" @endif value="active">Active</option>
									<option @if(isset($banner->status) && $banner->status=='inactive') selected="" @endif value="inactive">In-Active</option>
								</select>
							</div>
						</fieldset>
						<div class="text-right">
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
@endsection
@section('script')
<script type="text/javascript">
	"use strict"; 
	$('.daterange-basic1').daterangepicker({
		applyClass  : 'bg-slate-600',
		cancelClass : 'btn-default',
		locale    : {
			format  : 'YYYY-MM-DD'
		},
	});
</script>
@endsection