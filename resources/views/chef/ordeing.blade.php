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
			<h5><span class="text-semibold">Chef Ordering</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li><a href="{!!url(getRoleName().'/chef'.$url)!!}">All Chefs</a></li>
			<li class="active">Sorting Chefs</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
<div class="content">
	<form action="{!!url(getRoleName().'/chefordering')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
		{{ csrf_field() }}
		<div class="row drag-drop-asw ">
			<div class="col-md-6">
				<div class="panel panel-body border-top-info p-4">
					<div class="text-center">
						{{-- <h6 class="text-semibold no-margin py-3">Chef list</h6> --}}
						<p class="content-group-sm text-muted">Set priority of chef listing</p>
					</div>
					<ul class="dropdown-menu dropdown-menu-sortable" style="display: block; position: static; margin-top: 0; float: none;">
						@foreach($chefs as $key => $value)
						<li><input type="hidden" name="cOrder[]" value="{!! $value->id !!}"><a href="javascript::void();">{!! ucfirst($value->name) !!} <b>[ {!! $value->user_code !!} ]</b>
						@if($value->celebrity == 'yes')
						<span class="badge badge-primary">C</span>
						@elseif($value->promoted == 'yes')
						<span class="badge badge-info">P</span>
						@elseif($value->certified == 'yes')
						<span class="badge badge-danger">C</span>
						@elseif($value->type == 'event')
						<span class="badge badge-warning">E</span>
						@else
						<span class="badge badge-success">P</span>
						@endif
						</a></li>
						@endforeach
					</ul>
				</div>
				<div class="text-left">
					<button type="submit" class="btn btn-primary">Re order{{-- <i class="icon-arrow-right14 position-right"></i> --}}</button>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-body">
					<ul class="dropdown-menu dropdown-menu-sortable" style="display: block; position: static; margin-top: 0; padding: 10px;">
						<li><span class="badge badge-primary">C</span> - <span class="text-muted">Celebrity chefs</span></li>
						<li><span class="badge badge-info">P</span> - <span class="text-muted">Promoted chefs</span></li>
						<li><span class="badge badge-danger">C</span> - <span class="text-muted">Certified chefs</span></li>
						<li><span class="badge badge-success">P</span> - <span class="text-muted">Popular chefs</span></li>
						<li><span class="badge badge-warning">E</span> - <span class="text-muted">Events</span></li>
					</ul>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection			