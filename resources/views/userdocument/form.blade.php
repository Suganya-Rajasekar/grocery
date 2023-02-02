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
            <h5><span class="text-semibold">@if(getRoleName() == 'admin'){!! 'All chefs' !!}@else{!! 'Documents' !!}@endif</span> - @if(getRoleName() == 'admin'){!! 'Documents' !!}@else{!! 'Edit Documents' !!}@endif</h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            @if(getRoleName() == 'admin')
            <li><a href="{!! url('admin/chef'.$url) !!}">@if(getRoleName() == 'admin'){!! 'All chefs' !!}@else{!! 'Documents' !!}@endif</span></a></li>
            @endif
            <li class="active">@if(url(getRoleName()) == 'admin'){!! 'Documents' !!}@else{!! 'Edit Documents' !!}@endif</li>
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
    <form action="{!!url(getRoleName().'/chef/'.$v_id.'/user_documents/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}{{ method_field('POST') }}
        <input type="hidden" name="current_user_id" id="user_id" value="{!!isset($v_id) ? $v_id : ''!!}">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Basic documents</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <fieldset>

                            <div class="form-group">
                                <label class="text-semibold">FSSAI Certificate</label>
                                <div class="media no-margin-top">
                                    @if(isset($userdocument->fssai_certificate) && $userdocument->fssai_certificate != '')
                                    <?php $fssai_certificate = explode('.', $userdocument->fssai_certificate); ?>
                                    @if(isset($userdocument->fssai_certificate) && (!empty($fssai_certificate[1]) && ($fssai_certificate[1] == 'png' || $fssai_certificate[1] == 'jpg' || $fssai_certificate[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->fssai_certificate) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->fssai_certificate) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else
                                    <a href="{!!url($userdocument->fssai_certificate)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="fssai_certificate" id="fssai_certificate" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-semibold">Aadhar Card</label>
                                <select name="aadar_type" id="aadar_type" class="select" required="">
                                    <option  value="single">Single</option>
                                    <option  value="front_back">Front and Back</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="media no-margin-top">
                                    @if(isset($userdocument->aadar_image) && $userdocument->aadar_image != '')
                                    <?php $aadar_image = explode('.', $userdocument->aadar_image); ?>
                                    @if(isset($userdocument->aadar_image) && (!empty($aadar_image[1]) && ($aadar_image[1] == 'png' || $aadar_image[1] == 'jpg' || $aadar_image[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->aadar_image) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->aadar_image) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else 
                                    <a href="{!!url($userdocument->aadar_image)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                     <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="aadar_image" id="aadar_image" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div> 
                            </div>
                            <div id="aadhar" class="form-group  @if(isset($userdocument->aadar_type) && $userdocument->aadar_type == 'single') hidden @endif">
                             <div class="media no-margin-top">
                             @if(isset($userdocument->aadar_back) && $userdocument->aadar_back != '')
                                    <?php $aadar_back = explode('.', $userdocument->aadar_back); ?>
                                    @if(isset($userdocument->aadar_back) && (!empty($aadar_back[1]) && ($aadar_back[1] == 'png' || $aadar_back[1] == 'jpg' || $aadar_back[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->aadar_back) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->aadar_back) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else 
                                    <a href="{!!url($userdocument->aadar_back)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                     <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="aadar_back" id="aadar_back" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div> 
                            </div>

                            <div class="form-group">
                                <label class="text-semibold">Pan Card</label>
                                <div class="media no-margin-top">
                                    @if(isset($userdocument->pan_card) && $userdocument->pan_card != '')
                                    <?php $pan_card = explode('.', $userdocument->pan_card); ?>
                                    @if(isset($userdocument->pan_card) && (!empty($pan_card[1]) && ($pan_card[1] == 'png' || $pan_card[1] == 'jpg' || $pan_card[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->pan_card) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->pan_card) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else 
                                    <a href="{!!url($userdocument->pan_card)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="pan_card" id="pan_card" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="display-block text-semibold"> GST number available</label>
                                {{-- <div class="col-lg-10"> --}}
                                    <label class="radio-inline">
                                        <input type="radio" name="gst_declaration" value="1" class="styled" {!! isset($userdocument->gst_declaration) && ($userdocument->gst_declaration == 1) ? 'checked' : ''  !!} @if(!isset($userdocument->gst_declaration)) {{'checked'}} @endif>
                                        Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gst_declaration" value="0" class="styled" {!! isset($userdocument->gst_declaration) && ($userdocument->gst_declaration == 0) ? 'checked' : ''  !!}>
                                        No
                                    </label>
                                {{-- </div> --}}
                            </div>

                            <div id="gstDiv" class="@if(isset($userdocument->gst_declaration) && $userdocument->gst_declaration == 0) d-none @endif">
                                <div class="form-group">
                                    <label class="text-semibold">GST Number</label>
                                    <input type="text"  class="form-control" name="gst_no" placeholder="Enter GST number"  id="gst_no" value="{!!isset($userdocument->gst_no) ? $userdocument->gst_no : '' !!}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-semibold lab_name">@if(isset($userdocument->gst_declaration) && $userdocument->gst_declaration == 0){!! 'GST declaration form' !!} <a href="{!!url('storage/app/public/gst_declaration.pdf')!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs ml-2" ><b><i class="fa fa-download"></i></b></button></a> @else{!! 'GST Certificate' !!}@endif </label>
                                <div class="media no-margin-top">
                                    @if(isset($userdocument->gst_certificate) && $userdocument->gst_certificate != '')
                                    <?php $gst_certificate = explode('.', $userdocument->gst_certificate); ?>
                                    @if(isset($userdocument->gst_certificate) && ((!empty($gst_certificate[1])) && ($gst_certificate[1] == 'png' || $gst_certificate[1] == 'jpg' || $gst_certificate[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->gst_certificate) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->gst_certificate) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else 
                                    <a href="{!!url($userdocument->gst_certificate)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="gst_certificate" id="gst_certificate" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Other documents</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="text-semibold">Onboarding Form <a href="{!!url('storage/app/public/on_boarding_form.pdf')!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs ml-2" ><b><i class="fa fa-download"></i></b></button></a></label>
                                <div class="media no-margin-top">
                                    @if(isset($userdocument->on_boarding_form) && $userdocument->on_boarding_form != '')
                                    <?php $on_boarding_form = explode('.', $userdocument->on_boarding_form); ?>
                                    @if((!empty($on_boarding_form[1]) && ($on_boarding_form[1] == 'png' || $on_boarding_form[1] == 'jpg' || $on_boarding_form[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->on_boarding_form) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->on_boarding_form) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else 
                                    <a href="{!!url('storage/user_document/'.$v_id.'/'.$userdocument->on_boarding_form)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="on_boarding_form" id="on_boarding_form" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-semibold">Enrollment Form <a href="{!!url('storage/app/public/enrollment_form.pdf')!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs ml-2" ><b><i class="fa fa-download"></i></b></button></a></label>
                                <div class="media no-margin-top">
                                    @if(isset($userdocument->enrollment_form) && $userdocument->enrollment_form != '')
                                    <?php $enrollment_form = explode('.', $userdocument->enrollment_form); ?>
                                    @if((!empty($enrollment_form[1]) && ($enrollment_form[1] == 'png' || $enrollment_form[1] == 'jpg' || $enrollment_form[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->enrollment_form) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->enrollment_form) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else 
                                    <a href="{!!url($userdocument->enrollment_form)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="enrollment_form" id="enrollment_form" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-semibold">Cancelled Cheque <a href="{!!url('storage/app/public/cancelled_cheque_form.pdf')!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download="">{{--<button type="button" class="btn btn-success btn-xs ml-2" ><b><i class="fa fa-download"></i></b></button>--}}</a></label>
                                <div class="media no-margin-top">
                                    @if(isset($userdocument->cancelled_cheque) && $userdocument->cancelled_cheque != '')
                                    <?php $cancelled_cheque = explode('.', $userdocument->cancelled_cheque); ?>
                                    @if(isset($userdocument->cancelled_cheque) && (!empty($cancelled_cheque[1]) && ($cancelled_cheque[1] == 'png' || $cancelled_cheque[1] == 'jpg' || $cancelled_cheque[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->cancelled_cheque) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->cancelled_cheque) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else 
                                    <a href="{!!url($userdocument->cancelled_cheque)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="cancelled_cheque" id="cancelled_cheque" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-semibold">Address Proof of the Kitchen Premises</label>
                                <div class="media no-margin-top">
                                    @if(isset($userdocument->address_proof) && $userdocument->address_proof != '')
                                    <?php $address_proof = explode('.', $userdocument->address_proof); ?>
                                    @if(isset($userdocument->address_proof) && (!empty($address_proof[1]) && ($address_proof[1] == 'png' || $address_proof[1] == 'jpg' || $address_proof[1] == 'jpeg')))
                                    <div class="media-left">
                                        <a href="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->address_proof) !!}"><img src="{!! url('storage/user_document/'.$v_id.'/'.$userdocument->address_proof) !!}" style="width: 40px;height: 40px;max-width: none;" alt=""></a>
                                    </div>
                                    @else 
                                    <a href="{!!url($userdocument->address_proof)!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs mr-2" ><b><i class="fa fa-download"></i></b></button></a>
                                    @endif
                                    @endif
                                    <div class="media-body text-nowrap">
                                        <input type="file" class="file-styled" name="address_proof" id="address_proof" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                        <span class="help-block">Accepted formats: png, jpg, jpeg, pdf, doc, docx. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @if(getRoleName() == 'admin')
                <div class="form-group">
                    <label class="control-label col-lg-2">Status</label>
                    <div class="col-lg-10">
                        <select name="status" id="status" class="select-search" required="">
                            <option value disabled>select any one</option>
                            <option @if(isset($userdocument->status) && $userdocument->status=='pending') selected="" @endif value="pending">Pending</option>
                            <option @if(isset($userdocument->status) && $userdocument->status=='approved') selected="" @endif value="approved">Approved</option>
                        </select>
                    </div>
                </div>
                @endif
                <div class="text-right">
                    <a href="@if(getRoleName() == 'admin'){!! url('admin/chef') !!}@else{!! url(getRoleName().'/dashboard') !!}@endif" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                    <button type="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
    <!-- /form horizontal -->
</div>
<!-- /content area -->
@endsection
@section('script')
<script type="text/javascript">
    $(".styled").uniform({
        radioClass: 'choice'
    });
    $(document).on('change','input[name=gst_declaration]',function (e) {
        if (this.value == '1') {
            $('#gstDiv').removeClass('d-none');
            $('.lab_name').text('GST certificate');
        } else {
            $('#gstDiv').addClass('d-none');
            $('.lab_name').html(`GST declaration form <a href="{!!url('storage/app/public/gst_declaration.pdf')!!}" data-placement="left" data-toggle="tooltip" title="Click to download uploaded Onboarding Form" download=""><button type="button" class="btn btn-success btn-xs ml-2" ><b><i class="fa fa-download"></i></b></button></a>`);
        }
    })
   $(document).ready(function(){
    $("select.select").change(function(){
        var value = $(this).children("option:selected").val();
        if (value == 'single') { 
            $('#aadhar').addClass('hidden');
            $('#aadar_back').attr('disabled',true);
        }else{
            $('#aadhar').removeClass('hidden');
            $('#aadar_back').removeAttr('disabled');
        }
    });
});
</script>
@endsection