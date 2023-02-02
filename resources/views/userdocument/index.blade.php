@extends('layouts.backend.app')
@section('content')
@include('flash::message')
@php 
$pages=[];
@endphp
<!-- Basic responsive configuration -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Manage Chef @if($chefreq==false) request @endif</h5>
		@if($chefreq==false)
		<div class="heading-elements">
			<ul class="icons-list">
				<li><a href="{!!route('admin.chef.create')!!}"><button type="button" class="btn bg-teal-400 btn-labeled pull-right"><b><i class="fa fa-cutlery"></i></b> {{ __('Add New') }}</button></a></li>
			</ul>
		</div>
		@endif
	</div>
	<table class="table datatable-responsive">
		<thead>
			<tr>
				<th>#</th>
				<th>Partner chef ID</th>
				<th>Location</th>
				<th>Chef Name</th>
				<th>Email</th>
				<th>Avatar</th>
				<th>Email verified</th>
				@if($chefreq==false) <th class="text-center">Actions</th> @endif
			</tr>
		</thead>
		<tbody>
			@if(count($chefs)>0)
			@foreach($chefs as $key=>$value)
			<tr>
				<td>{!!$key+1!!}</td>
				<td><a href="{!!url(getRoleName().'/chef/'.$value->id.'/edit')!!}" data-placement="left" data-toggle="tooltip" title="Click to edit profile">{!!$value->id!!}</a></td>
				<td>@if($value->getChefRestaurant){!!$value->getChefRestaurant->location_info->name!!}@endif</td>
				<td>{!!$value->name!!}</td>
				<td>{!!$value->email!!}</td>
				<td><img src="@if($value->avatar != ''){{url($value->avatar)}}@else{{url('/storage/app/public/avatar.png')}}@endif" style="width: 40px;height: 40px;max-width: none;" class="img-circle" alt=""></td>
				<td>
					@if($value->email_verified_at!='')
					<span class="label label-success">Verified</span>
					@else
					<span class="label label-danger">Not-Verified</span>
					@endif
				</td>
				@if($chefreq==false)
				<td class="text-center">									
						<a href="{!!url(getRoleName().'/chef/'.$value->id.'/edit_business')!!}" data-placement="left" data-toggle="tooltip" title="Click to update business profile"><button type="button" class="btn btn-success btn-xs" ><b><i class="fa fa-edit"></i></b></button></a>
					
						<a href="{!!url(getRoleName().'/chef/'.$value->id.'/addon')!!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit addons"><button type="button" class="btn btn-info btn-xs" ><b><i class="fa fa-puzzle-piece"></i></b></button></a>

						<!-- <a href="{!!url(getRoleName().'/chef/'.$value->id.'/unit')!!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit unit"><button type="button" class="btn btn-default btn-xs" ><b><i class="fa fa-puzzle-piece"></i></b></button></a> -->

						<a href="{!!url(getRoleName().'/chef/'.$value->id.'/menu_item')!!}" data-placement="left" data-toggle="tooltip" title="Click to add/edit Menu Item's"><button type="button" class="btn btn-warning btn-xs" ><b><i class="fa fa-list"></i></b></button></a>



						<!-- <a href="{!!url('chef/'.$value->id.'/category')!!}"><button type="button" class="btn btn-warning btn-xs" ><b><i class="fa fa-list"></i></b></button></a> -->					
				</td>
				@endif
			</tr>
			@endforeach
			@endif

		</tbody>

	</table>
</div>
<!-- /basic responsive configuration -->

<!-- Location modal -->
<div id="modal_location" class="modal fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Add/Edit Location</h5>
			</div>

			<form action="{!!url('location/store')!!}" method="POST" enctype="Multipart/form-data">
				{{ csrf_field() }}{{ method_field('PUT') }}
				<div class="modal-body">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label>Location name</label>
								<input type="text" name="l_name" id="l_name" placeholder="Location" class="form-control" required="">
							</div>

							<div class="col-sm-6">
								<label>Status</label>
								<select name="l_status" id="l_status" class="select-search" required="">
									<option value="">select any one</option>
									<option value="0">Active</option>
									<option value="1">In-Active</option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<input type="hidden" name="l_id" id="l_id">
					<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Location modal -->
@endsection
@section('script')
<script type="text/javascript">
	"use strict";	
	//response will assign this function
	$(document).on('click','.edit_popup',function(){
		$("#l_id").val($(this).attr('data-id'));
		$("#l_name").val($(this).attr('data-name'));
		$('#l_status option[value="'+$(this).attr('data-name')+'"]').attr("selected", "selected");
		$("#modal_location").modal('show');
	})
	 // Success notification
	 $('#pnotify-success').on('click', function () {
	 	new PNotify({
            title: 'Success notice',
            text: 'Check me out! I\'m a notice.',
            addclass: 'bg-success'
        });
	 });
</script>
@endsection
