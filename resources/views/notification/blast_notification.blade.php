@extends('layouts.backend.app')
@section('page_header')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Review - Edit Review</h5>
			</div>
		</div>
		<div class="breadcrumb-line">
			<ul class="breadcrumb">
				<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
				<li><a href="{!!url(getRoleName().'/blast_notification')!!}">Blast Notification</a></li>
			</ul>
		</div>
	</div>
	@endsection
	@section('content')
	<a href="{{ URL::to(getRoleName().'/blast_notification/logs') }}"><button type="button" class="btn btn-primary">Logs</button></a>
	<form action="{!!url(getRoleName().'/notification_send')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
		{{ csrf_field() }}
     <div class="col-md-12">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h5 class="panel-title">Notification details</h5>
				</div>
				<div class="panel-body">
           <div class="row">
					<fieldset class="content-group col-lg-6">
						<div class="form-group">
							<label class="text-semibold">Subject</label>
							<input type="text" class="form-control" name="subject" required>
						</div>
						<div class="form-group">
							<label class="text-semibold">Message</label>
							<textarea name="message" rows="10" cols="3" class="form-control limitcount" required></textarea>
						</div>  
          </fieldset>
           <fieldset class="col-lg-6">
           				<div class="form-group">
							<label class="text-semibold">Users</label>
							<div>
								<label class="radio-inline">	
									<input type="radio" class="users" name="users" value="none" checked> None
								</label>
								<label class="radio-inline">	
									<input type="radio" class="users" name="users" value="all_users"> All Users
								</label>
								<label class="radio-inline" style="margin-left:30px;">
									<input type="radio" class="users" name="users" value="selected_users"> Selected Users
								</label>
							</div>
						</div>
						<div class="form-group" id="users_list" style="display:none;">
							<label class="text-semibold">Select Users</label>
							<select name="users[]" class="select-search" multiple="">
                                <option value="" disabled="">Select a user</option>
								@foreach($users as $key => $val)
								<option value="{{ $val->id }}">{{ $val->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label class="text-semibold">Chefs</label>
							<div>
								<label class="radio-inline">	
									<input type="radio" class="chefs" name="chefs" value="none" checked> None
								</label>
								<label class="radio-inline">	
									<input type="radio" class="chefs" name="chefs" value="all_chefs"> All Chefs
								</label>
								<label class="radio-inline" style="margin-left:30px;">
									<input type="radio" class="chefs" name="chefs" value="selected_chefs"> Selected Chefs
								</label>
							</div>
						</div>
						<div class="form-group" id="chefs_list" style="display:none;">
							<label class="text-semibold">Select Chefs</label>
							<select name="chefs[]" class="select-search" multiple="">
                                <option value="" disabled="">Select a chef</option>
								@foreach($chefs as $key => $val)
								<option value="{{ $val->id }}">{{ $val->name }}</option>
								@endforeach
							</select>
						</div>	
						<div class="form-group">
							<label class="text-semibold">Status</label>
							<select name="status" id="status" class="select-search" required="">
								<option value="approved">Approved</option>
								<option value="pending">Pending</option>
								<option value="suspended">Rejected</option> 
							</select>
						</div>
					</fieldset>
        </div>
					<div class="text-right">
						<button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
						<a href="{!! url(getRoleName().'/blast_notification') !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script>
		$(document).on('change','.users',function(){
			$('#users_list').hide();
			var option = $(this).val();
			if(option == 'selected_users'){
				$('#users_list').show();
			}
		});
		$(document).on('change','.chefs',function(){
			$('#chefs_list').hide();
			var option = $(this).val();
			if(option == 'selected_chefs'){
				$('#chefs_list').show();
			}
		});
	</script> 
	@endsection
