@extends('layouts.backend.app')
@section('content')
<!-- Content area -->
<div class="content">
	<!-- Form horizontal -->
	<div class="panel panel-flat">
		<div class="panel-heading">
			<h5 class="panel-title">Create/Edit Category</h5>
			{{-- <div class="heading-elements">
				<ul class="icons-list">
					<li><a data-action="collapse"></a></li>
				</ul>
			</div> --}}
		</div>
		<div class="panel-body">
			<form action="{!!url('/category/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
				{{ csrf_field() }}
				<!-- {{ method_field('PUT') }} -->
				<input type="hidden" name="id" id="id" value="{!! isset($category->id) ? $category->id : ''!!}">
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-flat">
							<div class="panel-heading">
								<h5 class="panel-title">Basic details</h5>
								<div class="heading-elements">
									<ul class="icons-list">
										<li><a data-action="collapse"></a></li>
										{{-- <li><a data-action="reload"></a></li> --}}
										{{-- <li><a data-action="close"></a></li> --}}
									</ul>
								</div>
							</div>
							<div class="panel-body">
								<fieldset>
									<div class="form-group">
										<label class="text-semibold">Name</label>
										<input type="text" required class="form-control" name="name" placeholder="Enter name" id="name" value="{!! isset($category->name) ? $category->name : old('name') !!}">
									</div>
									@if(count($store) > 0)
									<div class="form-group">
										<label class="text-semibold">Store</label>
										<select required name="res_id" id="res_id" class="select-search">
											<option disabled selected value> Select store </option>
											@foreach($store as $key => $value)
											@if(isset($category->res_id))
											<option @if(in_array($value->id,explode(',',$category->res_id))) selected @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
											@else
											<option value="{!! $value->id !!}">{!! $value->name !!}</option>
											@endif
											@endforeach
										</select>
									</div>
									@else
									<input type="hidden" name="res_id" value="0">
									@endif
									<div class="form-group">
										<label class="text-semibold">Store</label>
										<select required name="res_id" id="res_id" class="select-search">
											<option disabled selected value> Select store </option>
											@foreach($store as $key => $value)
											@if(isset($category->res_id))
											<option @if(in_array($value->id,explode(',',$category->res_id))) selected @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
											@else
											<option value="{!! $value->id !!}">{!! $value->name !!}</option>
											@endif
											@endforeach
										</select>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-flat">
							<div class="panel-heading">
								<h5 class="panel-title">Secondary details</h5>
								<div class="heading-elements">
									<ul class="icons-list">
										<li><a data-action="collapse"></a></li>
										{{-- <li><a data-action="reload"></a></li> --}}
										{{-- <li><a data-action="close"></a></li> --}}
									</ul>
								</div>
							</div>
							<div class="panel-body">
								<fieldset>
									@if(isset($category->id))
									<div class="form-group">
										<label class="text-semibold">Slug</label>
										<input type="text" required class="form-control" name="slug" placeholder="Enter slug" id="slug" value="{!! isset($category->slug) ? $category->slug : old('slug') !!}">
									</div>
									@endif
									<div class="form-group">
										<label class="text-semibold">Image</label>
										<div class="media no-margin-top">
											@if(isset($category->avatar))
											<div class="media-left">
												<a href="{!! url($category->avatar) !!}" download="{{ substr(strrchr($category->avatar,'/'),1) }}"><img src="{!! url($category->avatar) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
											</div>
											@endif
											<div class="media-left" style="display: none;">
												<img src="" id="item-img-output" style="width: 58px; height: 58px; border-radius: 2px;" alt="Cropped Image">
											</div>
											<div class="media-body text-nowrap">
												<input type="file" class="file-styled" name="avatar" id="imageid" accept="image/png, image/jpeg, image/jpg">
												<span class="help-block">Accepted formats: jpeg, png, jpg. Max file size 2Mb</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="text-semibold">Status</label>
										<select required name="status" id="status" class="select-search">
											<option disabled hidden value="">Select any one</option>
											<option @if(isset($category->status) && $category->status=='active') selected="" @endif value="active">Active</option>
											<option @if(isset($category->status) && $category->status=='inactive') selected="" @endif value="inactive">Inactive</option>
											<option @if(isset($category->status) && $category->status=='declined') selected="" @endif value="declined">Declined</option>
											<option @if(isset($category->status) && $category->status=='p_inactive') selected="" @endif value="p_inactive">In-Active by partner</option>
										</select>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="text-right">
						<a href="{!! url('') !!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
						<button type="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
					</div>
				</div>
				{{-- <fieldset class="content-group">
					<legend class="text-bold">Basic details</legend>
					<div class="form-group">
						<label class="control-label col-lg-2">Name</label>
						<div class="col-lg-10">
							<input type="text"  class="form-control" name="name" placeholder="Enter name"  id="name" value="{!!isset($category->name) ? $category->name : ''!!}" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-2">Content</label>
						<div class="col-lg-10">
							<input type="text"  class="form-control" name="content" placeholder="Enter content"  id="content" value="{!!isset($category->content) ? $category->content : ''!!}" required="">
						</div>
					</div>
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
				</div> --}}
			</form>
		</div>
	</div>
	<!-- /form horizontal -->
</div>
<!-- /content area -->
@endsection