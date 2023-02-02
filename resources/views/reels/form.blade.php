@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">@if(isset($reels)) Edit @else Create @endif Reel</h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li><a href="{!! Cache::has('reels_backurl') ? cache::get('reels_backurl') : url(getRoleName().'/reels')!!}">reels</a></li>
			<li class="active">@if(isset($reels)) {!! 'Edit reel' !!} @else {!! 'Create reel' !!} @endif </li>
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


			<form action="{!!url(getRoleName().'/reels')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
				{{ csrf_field() }}
				<input type="hidden" name="id" value="{{ isset($reels->id) ? $reels->id : ''}}">
				<div class="container-fluid">
					<div class="row">
						<fieldset class="col-lg-6"> 
							<div class="form-group">
								<label class="text-semibold">Title</label>
								<input type="text"  class="form-control" name="title" placeholder="Enter reel title" value="{{ isset($reels->title) ? $reels->title : ''}}" required="">
							</div>
							<div class="form-group">
								<label class="text-semibold">Description</label>
								<textarea class="form-control" name="description" placeholder="Enter reel description" rows="5" cols="5" required="">{{ isset($reels->description) ? $reels->description : ''}}</textarea>
							</div>
							<div class="form-group ">
								<label class="text-semibold">Reel video</label>
								<div class="media no-margin-top">
									@if(isset($reels->id))
									<div class="media-left">
										<div class="media-left">
											<iframe src="{!! $reels->video_magekit_thumburl !!}" name="test" height="200" width="200">You need a Frames Capable browser to view this content.</iframe>   
										</div>
									</div>
									@endif
									<div class="media-body text-nowrap">
										<input type="file"  class="file-styled theme_img1" name="reels" accept="video/mp4" value="{{ isset($reels->video_url) ? $reels->video_url : '' }}">
										<span class="help-block">Accepted format: mp4.</span>
										{{-- <span class="help-block">Only allow 4 images</span> --}}
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset class="col-lg-6"> 
							<div class="form-group">
								<label class="display-block  text-semibold">is selected chef </label>
								<label class="radio-inline"><input type="radio"  class="form-control styled is_chef_selected" name="is_chef_selected" value="yes" required="" @if(isset($reels->is_chef_selected) && $reels->is_chef_selected == "yes") checked @endif>Yes</label>
								<label class="radio-inline"><input type="radio"  class="form-control styled is_chef_selected" name="is_chef_selected" value="no" required="" @if(isset($reels->is_chef_selected) && $reels->is_chef_selected == "no") checked @endif>No</label>
							</div>
							<div class="form-group" id="chef_selection" @if(isset($reels->is_chef_selected) && $reels->is_chef_selected == "no") style="display:none";@endif>
								<label class="text-semibold">Select chef</label>
								<select class="form-control select-search" name="selected_chef_id" id="selected_chef_id">
									<option value="">Select any one</option>
									@foreach($chefs as $key => $cvalue)
									<option value="{{ $cvalue->id }}" @if(isset($reels->selected_chef_id) && $reels->selected_chef_id == $cvalue->id) selected @endif>{{ $cvalue->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label class="text-semibold">Validity date time</label>
								<input type="text" class="form-control" name="validity" id="validity" autocomplete="off" value="{{ isset($reels->validity_date_time) ? $reels->validity_date_time : '' }}">
							</div>
						</fieldset>
					</div>
				</div>
				<div class="form-group col-12">
					<label class="text-semibold">Status</label>
					<select class="form-control select-search" name="status">
						<option>Select any one</option>
						<option @if(isset($reels->status) && $reels->status == 'active') selected  @endif>Active</option>
						<option @if(isset($reels->status) && $reels->status == 'inactive') selected  @endif>Inactive</option>
					</select>
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
	$('#validity').datetimepicker({
		format: 'Y-m-d H:i:s',
		formatTime:"h:i a",
		step:30,
		minDate:0,
		onSelectTime:function(curren_time,input){
			let datetime = input.val().split(" ");
			let time = datetime[1].split(":");
			time[2]  = '00';
			let changed_time = datetime[0]+" "+time[0]+":"+time[1]+":"+time[2];
			$('#validity').val(changed_time);
		}
	});
	$(document).on('click','.is_chef_selected',function(){
		$('#chef_selection').hide(); 
		$("#selected_chef_id").val('');
		$("#selected_chef_id").trigger('change');
		if($(this).val() == 'yes') {
			$('#chef_selection').show(); 
		} 
	});
</script>
@endsection
