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
            <h5><span class="text-semibold">Promo - @if(!$offer) {!! 'Add Promo' !!} @else  {!! 'Edit Promo' !!} @endif</span></h5>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            <li><a href="{!!url(getRoleName().'/offer'.$url)!!}">Promo</a></li>
            <li class="active">@if(!$offer) {!! 'Add Promo' !!} @else  {!! 'Edit Promo' !!} @endif</li>
        </ul>
    </div>
</div>
<!-- /page header -->
@endsection
@section('content')
<!-- Content area -->
<div class="content">
    <!-- Form horizontal -->
   <?php //echo $offer->image;echo "<pre>"; print_r($offer);echo "</pre>";exit;?>
            <form action="{!!url(getRoleName().'/offer/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" name="id" id="id" value="{!!isset($offer->id) ? $offer->id : ''!!}">
                {{-- <div class="panel-heading">
                    <h3 class="panel-title">Promo offer {!!isset($offer->id) ? 'edit' : 'create'!!}</h3>
                    <hr>
                </div> --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset>
                                    <legend class="text-semibold">
                                        <h6>
                                            <i class="icon-file-text2 position-left"></i>
                                            Basic details
                                            <a class="control-arrow" data-toggle="collapse" data-target="#basic">
                                                <i class="icon-circle-down2"></i>
                                            </a>
                                        </h6>
                                        <hr>
                                    </legend>
                                    <div id="basic">
                                        <div class="form-group">
                                            <label class="text-semibold">Promo Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Enter Offer Name" id="name" value="{!! $offer->name ?? '' !!}">
                                        </div>
                                        <div class="form-group">
                                            <?php
                                            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                                            $code = "";
                                            for ($i = 0; $i < 7; $i++) {
                                                $code .= $chars[mt_rand(0, strlen($chars)-1)];
                                            }
                                            ?>
                                            <label class="text-semibold">Promo Code</label>

                                            <input class="form-control" placeholder="" required="" id="promo_code" name="promo_code" type="text" value="{!! isset($offer->promo_code) ? $offer->promo_code : $code !!}">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-semibold">Promo Description</label>

                                            <textarea class="form-control" placeholder="" required="true" name="promo_desc" cols="6" rows="2">{!! $offer->promo_desc ?? '' !!}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-semibold">Offer image</label>
                                            <div class="media no-margin-top">
                                                @if($offer->image ?? '')
                                                <div class="media-left">
                                                    <a href="{!! $offer->image !!}"><img src="{!! $offer->image !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                                                </div>
                                                @endif
                                                <div class="media-body text-nowrap">
                                                    <input type="file" class="file-styled" name="image" id="imageid" accept="image/png, image/jpeg" {!! isset($offer->image) ? '' : 'required=""' !!}>
                                                    <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                                </div>
                                            </div>
                                        </div>
                                      
                                        <div class="form-group">
                                            <label class="display-block text-semibold"> Choose Location</label>
                                            <label class="radio-inline">
                                                <input type="radio" class="loc_res" name="loc_status" value="all" class="styled" {!! isset($offer->loc_status) && ($offer->loc_status == 'all') ? 'checked' : ''  !!} required>
                                                All Location
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="loc_res" name="loc_status" value="selected" class="styled" {!! isset($offer->loc_status) && ($offer->loc_status == 'selected') ? 'checked' : ''  !!}>
                                                Select Location
                                            </label>
                                        </div>
                                        <?php $locations = App\Models\Location::all(); 
                                        ?>
                                        <div class="form-group res_tab" id="res_tab"  style="display:@if(isset($offer->loc_status) && ($offer->loc_status == 'selected')) block @else none @endif">
                                            <label class="text-semibold">Location</label>

                                            <select name="location[]" class="select-search" multiple="">
                                                <option value="" disabled="">Select a Location</option>
                                                @if(count($locations)>0)
                                                @foreach($locations as $key=>$value)
                                                @if(isset($offer->location))
                                                <option @if(in_array($value->id,explode(',',$offer->location)))  selected="" @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
                                                @else
                                                <option value="{!! $value->id !!}">{!!$value->name!!}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label class="display-block text-semibold"> Choose Chef</label>
                                            <label class="radio-inline">
                                                <input type="radio" class="chef_res" name="res_status" value="all" class="styled" {!! isset($offer->res_status) && ($offer->res_status == 'all') ? 'checked' : ''  !!} required>
                                                All Chef
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="chef_res" name="res_status" value="selected" class="styled" {!! isset($offer->res_status) && ($offer->res_status == 'selected') ? 'checked' : ''  !!}>
                                                Select Chef
                                            </label>
                                        </div>

                                        <div class="form-group chef_tab" id="chef_tab"  style="display:@if(isset($offer->res_status) && ($offer->res_status == 'selected')) block @else none @endif">
                                            <label class="text-semibold">Chefs</label>
                                            <select name="restaurant[]" class="select-search" multiple="">
                                                <option value="" disabled="">Select a Chef</option>
                                                @if(count($chefs)>0)
                                                @foreach($chefs as $key=>$value)
                                                @if(isset($offer->restaurant))
                                                <option @if(in_array($value->id,explode(',',$offer->restaurant)))  selected="" @endif value="{!! $value->id !!}">{!! $value->name !!}</option>
                                                @else
                                                <option value="{!! $value->id !!}">{!!$value->name!!}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="display-block text-semibold"> Offer Visibility </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="chef_res" name="offer_visible" value="on" class="styled" {!! isset($offer->offer_visibility) && ($offer->offer_visibility == 'on') ? 'checked' : ''  !!} required>
                                                On
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="chef_res" name="offer_visible" value="off" class="styled" {!! isset($offer->offer_visibility) && ($offer->offer_visibility == 'off') ? 'checked' : ''  !!}>
                                                Off
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-semibold">Status</label>
                                            <select name="status" id="status" class="select-search" required="">
                                                <option value="">select any one</option>
                                                <option @if(isset($offer->status) && $offer->status=='1') selected="" @endif value="1">Active</option>
                                                <option @if(isset($offer->status) && $offer->status=='0') selected="" @endif value="0">In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset>
                                    <legend class="text-semibold">
                                        <h6>
                                            <i class="icon-percent position-left"></i>
                                            Offer details
                                            <a class="control-arrow" data-toggle="collapse" data-target="#validity">
                                                <i class="icon-circle-down2"></i>
                                            </a>
                                        </h6>
                                        <hr>
                                    </legend>
                                    <div id="validity">
                                        <div class="form-group">
                                            <label class="display-block text-semibold">Promo Type</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="promo_type" value="amount" class="styled" {!! isset($offer->promo_type) && ($offer->promo_type == 'amount') ? 'checked' : ''  !!} >
                                                Amount
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="promo_type" value="percentage" class="styled" {!! isset($offer->promo_type) && ($offer->promo_type == 'percentage') ? 'checked' : ''  !!}>
                                                Percentage
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-semibold">Promo Offer value</label>
                                            <input type="number" class="form-control" name="offer" placeholder="Enter Offer Percentage" id="offer" value="{!! $offer->offer ?? '' !!}">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-semibold">Min Order Amount</label>
                                            <input type="text" class="form-control" name="min_order_value" placeholder="" id="min_order_value" value="{!! $offer->min_order_value ?? '' !!}">
                                            <span class="help-block">Note: If you dont want to set minimum order value, then just give "0"</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-semibold"> Maximum discount</label>
                                            <input type="text" class="form-control" name="max_discount" placeholder="" id="max_discount" value="{!! $offer->max_discount ?? '' !!}">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend class="text-semibold">
                                        <h6>
                                            <i class="icon-hour-glass2 position-left"></i>
                                            Validity details
                                            <a class="control-arrow" data-toggle="collapse" data-target="#offer">
                                                <i class="icon-circle-down2"></i>
                                            </a>
                                        </h6>
                                        <hr>
                                    </legend>
                                        <hr>
                                    <div id="offer">
                                        <div class="form-group">
                                            <label class="text-semibold">Date validity</label>
                                            <input type="text" class="form-control daterange_basic" name="date" value="{!! isset($offer->start_date) && ($offer->start_date!='' && $offer->end_date!='') ? date('Y-m-d',strtotime($offer->start_date)).' - '.date('Y-m-d',strtotime($offer->end_date)) : '' !!}"> 
                                        </div>
                                        <div class="form-group">
                                            <label class="text-semibold">Usage Limit</label>
                                            <input type="text" class="form-control" name="usage_limit" placeholder="" id="usage_limit" value="{!! $offer->usage_limit ?? '' !!}">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-semibold">Number of usage to single customer </label>
                                            <input type="text" class="form-control" name="user_limit" placeholder="" id="user_limit" value="{!! $offer->user_limit ?? '' !!}">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /form horizontal -->
<!-- </div> -->
<!-- /content area -->
@endsection
@section('script')
<script type="text/javascript">
    <?php
           if(isset($offer->start_date) && ($offer->start_date!='' && $offer->end_date!='')){
                $dt1 = date('Y-m-d',strtotime($offer->start_date));
                $dt2 = date('Y-m-d',strtotime($offer->end_date));
            }else{
                $dt1 = date('Y-m-d');
                $dt2 = date('Y-m-d', strtotime('+1 month'));
            }   
        ?>
        var startDate   = "{!! $dt1 !!}";
        var endDate     = "{!! $dt2 !!}"; 
        $('.daterange_basic').daterangepicker({
            applyClass  : 'bg-slate-600',
            cancelClass : 'btn-default',
            startDate   : startDate,
            endDate     : endDate,
            locale      : {
                format  : 'YYYY-MM-DD'
            },
            "isInvalidDate": function(date){
                if(date.format('YYYY-MM-DD') < endDate){
                    return true;
                }
                else {
                    return false;
                }
            },
        }, function (start_date,end_date) {
            $('#start_date').val(start_date.format('YYYY-MM-DD')+' - '+end_date.format('YYYY-MM-DD'));
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