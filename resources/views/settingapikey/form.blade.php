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
            <h5><span class="text-semibold">SettingsApiKey - @if(!$settingsapikey) {!! 'Add APi Key' !!} @else  {!! 'Edit Api Key' !!} @endif</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/settings'.$url)!!}">Settings</a></li>
            <li class="active">@if(!$settingsapikey) {!! 'Add Settings' !!} @else  {!! 'Edit Settings' !!} @endif</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content">
    <form action="{!!url(getRoleName().'/settings/updateapikey')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}{{ method_field('POST') }}
       <input type="hidden" name="id" id="id" value="{!!isset($settingsapikey->id) ? $settingsapikey->id : ''!!}">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Api Key</h5>
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
                                <label class="text-semibold">Map Key</label>
                                <input type="text"  class="form-control" name="mapkey" placeholder="Enter Map Key"  id="mapkey" value="{!!isset($settingsapikey->map_key) ? $settingsapikey->map_key : ''!!}" required="">
                            </div>

                            
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Social Key</h5>
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
                                
                                <label class="text-semibold">Facebook Client Id</label>
                                <input type="text"  class="form-control" name="facebookclientid" placeholder="Enter Facebook Client Id"  id="facebookclientid" value="{!!isset($settingsapikey->facebook_client_id) ? $settingsapikey->facebook_client_id : ''!!}" required="">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Facebook Client Secret</label>
                                <input type="text"  class="form-control" name="facebookclientsecret" placeholder="Enter Facebook Client Secret"  id="facebookclientsecret" value="{!!isset($settingsapikey->facebook_client_secret) ? $settingsapikey->facebook_client_secret : ''!!}" required="">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Facebook Callback Url</label>
                                 <input type="text"  class="form-control" name="facebook_redirect" placeholder="Enter Facebook Callback Url"  id="facebook_redirect" value="{!!isset($settingsapikey->facebook_redirect) ? $settingsapikey->facebook_redirect : ''!!}" required="">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Google Client Id</label>
                                <input type="text"  class="form-control" name="googleclientid" placeholder="Enter Google Client Id"  id="googleclientid" value="{!!isset($settingsapikey->google_client_id) ? $settingsapikey->google_client_id : ''!!}" required="">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Google Client Secret</label>
                                <input type="text"  class="form-control" name="googleclientsecret" placeholder="Enter Google Client Secret"  id="googleclientsecret" value="{!!isset($settingsapikey->google_client_secret) ? $settingsapikey->google_client_secret : ''!!}" required="">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Google Callback Url</label>
                                <input type="text"  class="form-control" name="googleredirect" placeholder="Enter Google Callback Url"  id="googleredirect" value="{!!isset($settingsapikey->google_redirect) ? $settingsapikey->google_redirect : ''!!}" required="">
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="text-right">
                    <a href="{!!url(getRoleName().'/settings'.$url)!!}" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                    <button type="submit" name="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /content area -->
@endsection