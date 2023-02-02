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
			<h5><span class="text-semibold">Master -Whats trending</h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li><a href="{!!url(getRoleName().'/whats_trending'.$url)!!}">Whats Trending</a></li>
			<li class="active">{!! 'Edit whats trending' !!}</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
{{-- <?php echo "<pre>";print_r($trending);exit;?> --}}
<div class="content">
	<!-- Form horizontal -->
	<form action="{!!url(getRoleName().'/whats_trending/store')!!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<div class="col-md-6">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title">Basic details</h5>
				</div>
				<div class="panel-body">
					<input type="hidden" name="c_id" id="c_id" value="{!!isset($trending->id) ? $trending->id : ''!!}">
					<fieldset>
						<div class="form-group">
							<label>name</label>
							<input type="text" name="c_name" class="form-control" id="c_name" placeholder="Enter name" value="{!!isset($trending->name) ? $trending->name : ''!!}" required="">
						</div>
						<div class="form-group">
							<label class="text-semibold">Image</label>
							<div class="media no-margin-top">
								@if(isset($trending->image))
								<div class="media-left">
									<a href="{!! url($trending->image) !!}" target="_blank"><img src="{!! url($trending->image) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
								</div>
								<div class="media-left" style="display: none;">
									<img src="" id="item-img-output" style="width: 58px; height: 58px; border-radius: 2px;" alt="Cropped Image">
								</div>
								@endif
								<div class="media-body text-nowrap">
									<input type="file" class="file-styled" name="image" id="imageid" accept="image/png, image/jpeg, image/jpg">
									<span class="help-block">Accepted formats: png, jpg. Max file size 2Mb</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Anyone Choose : </label>
							<input type="radio" name="type" class="choose_video" value="url" {!!(isset($trending->type) && $trending->type=='url') ? 'checked' : '' !!} required> Video url
							<input type="radio" name="type" class="choose_video" value="video" {!!(isset($trending->video) && $trending->type=='video') ? 'checked' : '' !!} required> Video upload
						</div>
						<div class="form-group description" {!!(isset($trending->type) && $trending->type=='url') ? '' : 'style="display: none;"' !!}>
							<label>Video url</label>
							<input type="text" name="video_url" class="form-control" placeholder="Enter video url" value="{!!isset($trending->video) ? $trending->video : ''!!}">
						</div>
						<div class="form-group video_upload" {!!(isset($trending->type) && $trending->type=='video') ? '' : 'style="display: none;"' !!}>
							<label class="text-semibold">video upload</label>
							<div class="media no-margin-top">
								@if(isset($trending->video) && $trending->video!='')
								<div class="media-left">
									<iframe src="{!! $trending->video !!}" name="test" height="200" width="200">You need a Frames Capable browser to view this content.</iframe>
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
								<option value="active" @if(isset($trending->status) && $trending->status=='active') selected="" @endif>Active</option>
								<option value="inactive" @if(isset($trending->status) && $trending->status=='inactive') selected="" @endif>In-Active</option>
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
		var val	= $(this).val();
		if(val=='url'){
			$('.description').show();
			$('.video_upload').hide();
		} else {
			$('.description').hide();
			$('.video_upload').show();
		}
	});
</script>
@endsection