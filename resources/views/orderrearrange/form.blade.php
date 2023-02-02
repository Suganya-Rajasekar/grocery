@extends('layouts.backend.app')
@section('page_header')
<?php
	$chef   = getUserData(\Request::segment(3));
	$cpage  = request()->has('page') ? request()->get('page') : '';
	$ipage  = request()->has('innerpage') ? request()->get('innerpage') : '';
	$from   = request()->has('from') ? request()->get('from') : '';
	$url    = '?from='.$from.'&page='.$cpage;
	$url2   = $url.'&innerpage='.$ipage;
	$i      = ($innerpage > 0 && $innerpage != 1) ? ($innerpage - 1) * ($pCount + 1) : 1 ;
?>
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">@if(getRoleName() == 'admin'){!! $chef->name !!}@else{!! 'Menus' !!}@endif</span> - Menu item rearrangement</h5>
		</div>
	</div>
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			@if(getRoleName() == 'admin')
			<li><a href="{!! url('admin/chef'.$url) !!}">All chefs</a></li>
			@endif
			<li class="active">Rearrange menus categories</li>
		</ul>
	</div>
</div>
<div class="content">
	<!-- Form horizontal -->
	<form action="{!!url(getRoleName().'/menuitem/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input type="hidden" name="id" id="id" value="{!!isset($menuitem->id) ? $menuitem->id : ''!!}">
		{{-- <div class="panel-heading">
			<h3 class="panel-title">menuitem {!!isset($menuitem->id) ? 'edit' : 'create'!!}</h3>
			<hr>
		</div> --}}
		<div class="row drag-drop-asw ">
			<div class="col-md-6">
				<div class="panel panel-body border-top-info p-4">
					<ul class="dropdown-menu dropdown-menu-sortable" style="display: block; position: static; margin-top: 0; float: none;">
						<!-- <li class="dropdown-header">Menu header</li> -->
						@if(count($menuitem) > 0)
						@foreach($menuitem as $key=>$value)
						<li>
							<input type="hidden" name="restaurant_id" value="{!! $value->restaurant_id !!}">
                            <input type="hidden" name="category[]" value="{!! $value->categories->id !!}"><a href="#">{!! (isset($value->categories->name)) ? $value->categories->name : '' !!}</a>
						</li>
						@endforeach
						@endif
					</ul>
				</div>
				<div class="text-right">
					<button type="submit" class="btn btn-primary">Rearrange<i class="icon-arrow-right14 position-right"></i></button>
					<a href="@if(getRoleName() == 'admin'){!! url('admin/chef'.$url) !!}@else{!! url(getRoleName().'/dashboard') !!}@endif" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection
@section('script')
<script type="text/javascript">
	"use strict"; 
	$('.daterange-basic').daterangepicker({
		applyClass: 'bg-slate-600',
		cancelClass: 'btn-default'
	});
</script>
<script type="text/javascript">
	//location hide show
	$('.loc_res').on('click', function(event){  
		
		var value=$(this).val();
		if(value == 'all'){   
			$(".res_tab").css("display","none");
			//$(".loc_tab").css("display","block");
		}   
		else if(value == 'selected') {
			//$(".loc_tab").css("display","none");
			$(".res_tab").css("display","block");
		}     
	});
	// chef hide show
	$('.chef_res').on('click', function(event){  
		
		var value=$(this).val();
		if(value == 'all'){   
			$(".chef_tab").css("display","none");
			//$(".loc_tab").css("display","block");
		}   
		else if(value == 'selected') {
			//$(".loc_tab").css("display","none");
			$(".chef_tab").css("display","block");
		}     
	});
</script>
@endsection