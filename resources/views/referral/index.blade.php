@extends('layouts.backend.app')
@section('page_header')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h5><span class="text-semibold">Referral Settings</span></h5>
		</div>
	</div>

	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
			<li>Referral Settings</li>
		</ul>
	</div>
</div>
<!-- /page header -->
@endsection
@section('content')
@include('flash::message')
<div class="content">
    <form action="{!!url(getRoleName().'/referral/save')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
       <input type="hidden" name="id" id="id" value="">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Referral Friend</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="text-semibold">Wallet Amount</label>
                                <input type="text"  class="form-control" name="referral_user_credit_amt" placeholder="Enter amount" value="{{ isset($referral) ? $referral->referral_user_credit_amount : "" }}" required="">
                            </div>
                        </fieldset>
                         <fieldset>
                            <div class="form-group">
                                <label class="text-semibold">Referral share description</label>
                                <textarea rows="5" cols="5" class="form-control" name="referral_share_desc" placeholder="Enter referral share description" required="">{{ isset($referral) ? $referral->referral_share_description : ''}}</textarea>   
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Referred By existing user</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <fieldset>                         
                            <div class="form-group">                    
                                <label class="text-semibold">Wallet Amount</label>
                                <input type="text"  class="form-control" name="referrer_user_credit_amt" placeholder="Enter amount" value="{{ isset($referral) ? $referral->referer_user_credit_amount : "" }}" required="">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Referral user orders count</label>
                                <input type="text"  class="form-control" name="referral_user_orders_count" placeholder="Enter orders count" value="{{ isset($referral) ? $referral->referral_user_orders_count : "" }}" required="">
                                <small><span style="color: red;">Note:</span>per order count based Referred User get wallet amount</small>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="text-right">
                    <a href="{!!url(getRoleName().'/settings')!!}" class="btn btn-danger font-monserret">Cancel<i class="icon-cancel-circle2 position-right"></i></a>
                    <button type="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection