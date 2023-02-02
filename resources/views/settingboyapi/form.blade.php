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
            <h5><span class="text-semibold">SettingsBoyApi - @if(!$settingboyapi) {!! 'Add Setting' !!} @else  {!! 'Edit Settings' !!} @endif</span></h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/settings'.$url)!!}">Settings</a></li>
            <li class="active">@if(!$settingboyapi) {!! 'Add Settings' !!} @else  {!! 'Edit Settings' !!} @endif</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
<!-- Content area -->
<div class="content">
    <!-- Form horizontal -->
            <form action="{!!url(getRoleName().'/settingsboyapi/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="id" id="id" value="{!!isset($settingboyapi->id) ? $settingboyapi->id : ''!!}">
                {{-- <div class="panel-heading">
                    <h3 class="panel-title">ettingboyapi {!!isset($settingboyapi->id) ? 'edit' : 'create'!!}</h3>
                    <hr>
                </div> --}}
                <div class="row drag-drop-asw ">
                        <div class="col-md-4">
                            <div class="panel panel-body border-top-info p-4">
                                <div class="text-center">
                                    <h6 class="text-semibold no-margin py-3">Up to 4Kms</h6>
                                    <p class="content-group-sm text-muted">Set priority of Upto 4kms</p>
                                </div>

                                <ul class="dropdown-menu dropdown-menu-sortable" style="display: block; position: static; margin-top: 0; float: none;">
                                    <!-- <li class="dropdown-header">Menu header</li> -->
                                    @if(isset($settingboyapi->upto4))
                                    @php
                                    $upto=explode(',',$settingboyapi->upto4);
                                    @endphp
                                    <?php //echo "<pre>"; print_r($more);exit;?>
                                    @foreach($upto as $upto_k => $upto_v)
                                    <li><input type="hidden" name="upto4[]" value="{{$upto_v}}"><a href="#">{{ucfirst($upto_v)}}</a></li>
                                    @endforeach

                                    @else
                                    <li><input type="hidden" name="upto4[]" value="dunzo"><a href="#">Dunzo</a></li>
                                    <li><input type="hidden" name="upto4[]" value="shadowfax"><a href="#">Shadowfax</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="panel panel-body border-top-info p-4">
                                <div class="text-center">
                                    <h6 class="no-margin text-semibold py-3">More than 4Kms</h6>
                                    <p class="content-group-sm text-muted">Set priority of More than 4kms</p>
                                </div>

                                <ul class="dropdown-menu dropdown-menu-sortable" style="display: block; position: static; margin-top: 0; float: none;">
                                    <!-- <li class="dropdown-header">Menu header</li> -->
                                    @if(isset($settingboyapi->more4))
                                    @php
                                    $more=explode(',',$settingboyapi->more4);
                                    @endphp
                                    <?php //echo "<pre>"; print_r($more);exit;?>
                                    @foreach($more as $more_k => $more_v)
                                    <li><input type="hidden" name="more4[]" value="{{$more_v}}"><a href="#">{{ucfirst($more_v)}}</a></li>
                                    @endforeach

                                    @else
                                    <li><input type="hidden" name="more4[]" value="shadowfax"><a href="#">Shadowfax</a></li>
                                    <li><input type="hidden" name="more4[]" value="dunzo"><a href="#">Dunzo</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>



                        <div class="col-md-4">
                            <div class="panel panel-body border-top-info p-4">
                                <div class="text-center">
                                    <h6 class="no-margin text-semibold py-4">Delivery Setting</h6>
                                    <label class="w-100"><span>Delivery Amount / Sub-Order</span></label>
                                </div>

                                <ul class="dropdown-menu dropdown-menu-sortable" style="display: block; position: static; margin-top: 0; float: none;">
                                    <input type="text" name="amount" placeholder="Enter your Delivery Amount*" id="amount" class="form-control" value="{{$settingboyapi->amount}}">
                                </ul>
                                <div class="text-center">
                                    <h6 class="no-margin text-semibold py-4">Delivery Limit</h6>
                                </div>

                                <ul class="dropdown-menu dropdown-menu-sortable" style="display: block; position: static; margin-top: 0; float: none;">
                                    <input type="text" name="limit" placeholder="Enter your Delivery limit*" id="limit" class="form-control" value="{{$site_setting->nearby}}">
                                </ul>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- /dropdown menus -->
            </form>
        </div>
        <!-- /form horizontal -->
<!-- </div> -->
<!-- /content area -->
@endsection