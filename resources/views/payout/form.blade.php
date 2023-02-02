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
            <h5><span class="text-semibold">Master - TDS</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li class="active">{!! 'Edit TDS' !!}</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content pt-0">
    <!-- Form horizontal -->
    <form action="{!!url(getRoleName().'/tds/store')!!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('POST') }}
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Basic details</h5>
                </div>
                <div class="panel-body">
                    <fieldset>
                    <div class="row">
                        <div class="form-group col-md-6">
							<label> Chef Id</label>
								<select name='chef_id' rows='5' class="form-control" >
									@if(count($chef)>0)
									@foreach($chef as $uValue)
									<option value="{!!$uValue->id!!}">{!!$uValue->name!!}</option>
									@endforeach
									@endif </select>  
							</div> 	
                          <div class="form-group description col-md-6">
                            <label>FQ Quarter</label>
                            <input type="text" class="form-control" name="fq_quarter" placeholder="FQ quarter" id="name" value="{!!isset($tds->fq_quarter) ? $tds->fq_quarter : ''!!}">
                            
                        </div>
                          <div class="form-group description col-md-6">
                            <label>Start date</label>
                              <input type="date" class="form-control" name="start_date"  value="{!!isset($tds->start_date) ? $tds->start_date : ''!!}">     
                        </div>
                        <div class="form-group description col-md-6">
                            <label>End date</label>
                            <input type="date" class="form-control" name="end_date"  value="{!!isset($tds->end_date) ? $tds->end_date : ''!!}">
                            
                        </div>
                        <div class="form-group col-md-6">
                                <label>Certificate</label>
                                <div class="media no-margin-top">
                                    @if(isset($tds->certificate) && $tds->certificate != '')
                                    <?php $certificate = explode('.', $tds->certificate); ?>
                                    <div class="media-left">
                                        <a href="{!! url('storage/tds_document/'.$tds->certificate) !!}"><img src="{!! url('storage/tds_document/'.$certificate) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="certificate" id="certificate" accept="application/pdf">
                                        <span class="help-block">Accepted format:pdf Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <a href = "{{ url(getRoleName().'/payout/payout_tds') }}"  class="btn btn-primary">Cancel </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /form horizontal -->
</div>
<!-- /content area -->
@endsection
