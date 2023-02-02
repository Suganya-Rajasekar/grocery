@extends('layouts.backend.app')
@php
    $chef   = getUserData(\Request::segment(3));
    $cpage  = request()->has('page') ? request()->get('page') : '';
    $ipage  = request()->has('innerpage') ? request()->get('innerpage') : '';
    $from   = request()->has('from') ? request()->get('from') : '';
    $url    = '?from='.$from.'&page='.$cpage;
    $url2   = $url.'&innerpage='.$ipage;
    $madd_id[]  = '';
    if(isset($menuitem->addons)) {
        foreach($menuitem->addons as $madd_k => $madd_v) {
            $madd_id[]  = $madd_v->id;
        }
    }
    $selected_themes = (isset($menuitem->themes)) ? $menuitem->themes : '';
    $selected_preferences = (isset($menuitem->preferences)) ? $menuitem->preferences : '';
    $selected_meal = (isset($menuitem->meal)) ? $menuitem->meal : '';
@endphp
<!-- Page header -->
@section('page_header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h5><span class="text-semibold">@if(getRoleName() == 'admin'){!! $chef->name !!}@else{!! 'Menus' !!}@endif</span><span class="active_page">@if($menuitem == 'new') - Menu add @else - Menu edit @endif</span></h5>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{!! url(getRoleName().'/dashboard') !!}"><i class="icon-home2 position-left"></i>Dashboard</a></li>
            @if(getRoleName() == 'admin')
            <li><a href="{!! url('admin/chef/'.$url) !!}">All chefs</a></li>
            @endif
            <li><a href="@if(getRoleName() == 'admin'){!! url('admin/chef/'.\Request::segment(3).'/menu_item'.$url2) !!}@else{!! url(getRoleName().'/common/menu_item'.$url2) !!}@endif">Manage Menus</a></li>
            <li class="active active_page2">@if($menuitem == 'new') Add menu @else Edit menu @endif</li>
        </ul>
    </div>
</div>
@endsection
<!-- /Page header -->
@section('content')
@include('flash::message')
<!-- Content area -->
<div class="content">
    <input type="hidden" name="showpage" id="showpage" value="{{ $menuitem }}">
    <!-- Form horizontal -->
    <form action="{!!url(getRoleName().'/chef/'.$v_id.'/menu_item/store')!!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        <!-- {{ method_field('PUT') }} -->
        <input type="hidden" name="id" id="id" value="{!! isset($menuitem->id) ? $menuitem->id : '' !!}">
        <input type="hidden" name="res_type" id="res_type" value="{{ isset($restaurants->type) ? $restaurants->type : ''}}">
        <input type="hidden" name="page" value="{{ $cpage }}">
        <input type="hidden" name="from" value="{{ $from }}">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Basic details</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="text-semibold" id="dish_name">Name of @if($restaurants->home_event == 'yes') menu @else dish @endif</label>
                                <input type="text"  class="form-control" name="name" placeholder="Enter name"  id="name" value="{!!isset($menuitem->name) ? $menuitem->name : ''!!}" required="">
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Price (in Rs)</label>
                                <input type="text"  class="form-control" name="price" placeholder="Enter price"  id="price" value="{!!isset($menuitem->price) ? $menuitem->price : ''!!}" required="">
                            </div>
                            {{-- <div class="form-group  " > 
                                <label class="text-semibold"> Image </label>
                                <div class="col-md-10">
                                    <input  type='file' name='image[]' id='imageid' accept="image/png, image/jpeg" multiple='' @if(isset($menuitem->item_type) && $menuitem->image =='') parsley-validated required="" class='required' @endif style='width:150px !important;'  />
                                    <small>Size (512 * 512)</small>
                                    <div class="imagediv">
                                        @if(isset($menuitem->item_type) && $menuitem->image!='' && count(explode(",", $menuitem->image))>0)
                                        @foreach(explode(",", $menuitem->image) as $img)
                                        <img src = "{!! url($img) !!}" width="50" height="50">
                                        @endforeach
                                        @endif
                                    </div>
                                </div> 
                            </div> --}}
                            <div class="form-group ">
                                <label class="text-semibold" id="dish_image">@if($restaurants->home_event == 'yes') Menu @else Dish @endif Image</label>
                                <div class="media no-margin-top">
                                    @if(isset($menuitem->image))
                                    <div class="media-left">
                                        <a href="{!! url($menuitem->image) !!}" download="{{substr(strrchr($menuitem->image,'/'),1)  }}"><img src="{!! url($menuitem->image) !!}" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>
                                    </div>
                                    @endif
                                    <div class="media-left" style="display: none;">
                                        <img src="" id="item-img-output" src="" style="width: 58px; height: 58px; border-radius: 2px;" alt="Cropped Image">
                                    </div>
                                    <div class="media-body text-nowrap">
                                        <input type="file"  class="file-styled" name="image[]" id="imageid" accept="image/png, image/jpeg, image/jpg">
                                        <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-semibold">Category</label>
                                <select name="main_category[]" class="select-search" >
                                    <option value="">Select Category</option>
                                    @if(count($category) > 0)
                                        @foreach($category as $key => $value)
                                        @if(isset($menuitem->main_category))
                                            @if($restaurants->type == 'event') 
                                                @if($value->name == "Ticket")
                                                    <option @if(in_array($value->id,explode(',',$menuitem->main_category)))  selected="" @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                                @endif
                                            @elseif($restaurants->home_event == 'yes')
                                            @if($value->name == "Home Event")
                                                <option @if(in_array($value->id,explode(',',$menuitem->main_category)))  selected="" @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                            @endif
                                            @else 
                                                @if($value->name != "Ticket" && $value->name != "Home Event")
                                                    <option @if(in_array($value->id,explode(',',$menuitem->main_category)))  selected="" @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                                @endif
                                            @endif
                                        @else
                                            @if(isset($restaurants->type) && $restaurants->type == 'event') 
                                                @if($value->name == "Ticket")
                                                    <option value="{!!$value->id!!}">{!!$value->name!!}</option>
                                                @endif
                                            @elseif($restaurants->home_event == 'yes')
                                                @if($value->name == "Home Event")
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endif
                                            @else
                                                @if($value->name != "Ticket" && $value->name != "Home Event")
                                                    <option value="{!!$value->id!!}">{!!$value->name!!}</option>
                                                @endif
                                            @endif
                                        @endif
                                        @endforeach
                                   {{--  @else 
                                        <option value="ticket" @if(isset($menuitem->main_category) && $menuitem->main_category == '') selected @endif>Ticket</option> --}}
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" @if(isset($restaurants->type) ? $restaurants->type == 'event' : '') style="display:none;" @endif>
                                <label class="text-semibold">Addons</label>
                                <select name="addons[]" class="select-search" multiple="">
                                    <option value="" disabled>Select Addons</option>
                                    @if(count($addon)>0)
                                    @foreach($addon as $key=>$value)
                                    @if(isset($menuitem->addons))
                                    <option @if(in_array($value->id,$madd_id))  selected="" @endif value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @else
                                    <option value="{!!$value->id!!}">{!!$value->name!!}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @if($restaurants->home_event == 'yes')
                            <div class="form-group">
                                <label class="text-semibold">Themes</label>
                                <select name="themes[]" class="select-search" multiple="multiple">
                                    <option>Select Themes</option> 
                                    @if(count($themes)>0)
                                    @foreach($themes as $key =>$value)
                                    @if(!empty($menuitem->themes))
                                    <option  @if(in_array($value->id,explode(',',$selected_themes))) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>            
                                    @else
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endif            
                                    @endforeach    
                                    @endif                    
                                </select>
                            </div>
                            @endif
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Other details</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="form-group">
                                <label class="text-semibold">Description</label>
                                <textarea rows="5" cols="5" class="form-control limitcount" name="description" placeholder="Enter Description" required>{!! isset($menuitem->description) ? $menuitem->description : ''!!}</textarea>
                            </div>
                            @if(isset($restaurants->type) && $restaurants->type != 'event' && $restaurants->home_event != 'yes')
                            <div class="form-group">
                                <label class="display-block text-semibold">Preparation Time</label>
                                @if(isset($menuitem->id) && $menuitem->restaurant->preparation_time == 'ondemand' || $menuitem == 'new' && $restaurants->preparation_time == 'ondemand')
                                <label class="radio-inline">
                                    <input type="radio" name="preparation_time" value="1_to_2hrs" class="styled" {!! (isset($menuitem->id) && $menuitem->preparation_time == '1_to_2hrs') || ($menuitem == 'new') ? 'checked' : ''  !!} >
                                    1 to 2hrs
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="preparation_time" value="2_to_3hrs" class="styled" {!! (isset($menuitem->id) && $menuitem->preparation_time == '2_to_3hrs')  ? 'checked' : ''  !!} >
                                    2 to 3hrs
                                </label>
                                @endif
                                <label class="radio-inline">
                                    <input type="radio" name="preparation_time" value="tomorrow" class="styled" {!! (isset($menuitem->id) && ($menuitem->preparation_time == 'tomorrow' || $menuitem->restaurant->preparation_time == 'preorder')) || ($menuitem == 'new' && $restaurants->preparation_time == 'preorder') ? 'checked' : ''  !!} >
                                    Tomorrow
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="display-block text-semibold">Veg/Non_veg</label>
                                <label class="radio-inline">
                                    <input type="radio" name="item_type" value="veg"  class="styled"  @if(isset($menuitem->item_type)) {!! ($menuitem->item_type=='veg') ? 'checked="checked"' : ''  !!}  @else checked  @endif >
                                    Veg
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="item_type" value="nonveg" class="styled"  @if(isset($menuitem->item_type)) {!! ($menuitem->item_type=='nonveg') ? 'checked="checked"' : ''  !!} @endif>
                                    Non Veg
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="display-block text-semibold">Tags</label>
                                @if(isset($menuitem->tag_type))
                                <?php $tag = tags_status($menuitem->tag_type);?>
                                @elseif($menuitem == 'new')
                                <?php $tag = tags_status('newmenu');?>
                                @endif
                                <label>
                                    <input type="checkbox" class="menutag" name="tag_type[]" value="" id="tag_none" @if($tag['none'] == 1) checked @elseif(!isset($menuitem->tag_type) || (isset($menuitem->tag_type) && $menuitem->tag_type == '') || $menuitem == 'new') checked @endif><span class="ml-2">None</span>
                                </label>
                                <label class="ml-4">
                                    <input type="checkbox" class="menutag" name="tag_type[]" value="must try" id="must_try" @if($tag['must_try'] == 1) checked @endif><span class="ml-2">Must try</span>
                                </label>
                                <label class="ml-4">
                                    <input type="checkbox" class="menutag" name="tag_type[]" value="chef special" id="special" @if($tag['special'] == 1) checked @endif><span class="ml-2">Chef's special</span>
                                </label>
                                <label class="ml-4">
                                    <input type="checkbox" class="menutag" name="tag_type[]" value="bestseller" id="bestsell" @if($tag['bestsell'] == 1) checked @endif><span class="ml-2">BestSeller</span>
                                </label>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="display-block text-semibold">Stock status</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="stock_status" value="instock" class="styled" @if(isset($menuitem->stock_status)) {!! ($menuitem->stock_status=='instock') ? 'checked="checked"' : ''  !!}  @else checked @endif >
                                            In Stock
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="stock_status" value="outofstock" class="styled" @if(isset($menuitem->stock_status)) {!! ($menuitem->stock_status=='outofstock') ? 'checked="checked"' : ''  !!}  @endif>
                                            Out Of Stock
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group instock" @if(isset($menuitem->stock_status) && $menuitem->stock_status == 'outofstock') style="display:none;" @endif>
                                        <label class="text-semibold">Maximum order quantity</label>
                                        <input type="number"  class="form-control" name="quantity" placeholder="Enter quantity"  id="quantity" value="{!!isset($menuitem->quantity) ? $menuitem->quantity : 0!!}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-semibold">Discount</label>
                                            <input type="text"  class="form-control" name="discount" placeholder="Enter discount"  id="discount" value="{!!isset($menuitem->discount) ? $menuitem->discount : 0!!}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-semibold">Purchase quantity</label>
                                            <input type="text" class="form-control" name="purchase_quantity" placeholder="Enter purchase quantity" value="{{ isset($menuitem->purchase_quantity) ? $menuitem->purchase_quantity : 0}}">
                                        </div>
                                    </div>
                                </div>
                               {{--  <div class="col-md-6">
                                    <div class="form-group instock" @if(isset($menuitem->stock_status) && $menuitem->stock_status == 'outofstock') style="display:none;" @endif>
                                        <label class="text-semibold">Stock</label>
                                        <input type="number"  class="form-control" name="stock" placeholder="Enter quantity"  id="quantity" value="{!!isset($menuitem->stock) ? $menuitem->stock : 0!!}">
                                    </div>
                                </div> --}}
                            </div>
                            </div>
                            @endif
                            @if($restaurants->home_event == 'yes')
                            <div class="form-group">
                                <label class="text-semibold">Preferences</label>
                                <select name="preferences[]" class="select-search" multiple="multiple">
                                    <option>Select Preferences</option>         
                                    @if(count($preferences)>0)   
                                    @foreach($preferences as $key => $value)
                                    @if(!empty($menuitem->preferences))
                                    <option @if(in_array($value->id,explode(',',$selected_preferences))) selected @endif value="{{ $value->id }}">{{ $value->name }}</option>
                                    @else 
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endif            
                                    @endforeach           
                                    @endif             
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="text-semibold">Chef Meal</label>
                                    <select name="chef_meal[]" class="select-search" multiple="multiple">
                                        <option>Select chefmeal</option>
                                        <option value="Vegetarian" @if(in_array('Vegetarian',explode(',',$selected_meal))) selected @endif>Vegetarian</option>
                                        <option value="Non-Vegetarian" @if(in_array('Non-Vegetarian',explode(',',$selected_meal))) selected @endif>Non-Vegetarian</option>                   
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="text-semibold">Minimum Order quantity</label>
                                    <input type="text" class="form-control" name="minimum_quantity" placeholder="Enter purchase quantity" value="{{ isset($menuitem->minimum_order_quantity) ? $menuitem->minimum_order_quantity : 10}}">
                                </div>
                            </div>
                            @endif
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @if(isset($restaurants->type) && $restaurants->type != 'event' && $restaurants->home_event != 'yes')  
                <div class="form-group">
                    <label class="text-semibold">Variants</label>
                    <button type="button" class="btn btn-success unit_add" ><b><i class="fa fa-plus"></i></b></button>
                </div>
                <div class="unit_div">
                    @if(isset($menuitem->unit_det) && count($menuitem->unit_det) > 0)
                    <?php $Aunit=[]; ?>
                    @foreach($menuitem->unit_det as $uKy=>$uVl)
                    <div class="row unitdiv{!!$uKy!!}">
                        <div class="col-md-5">
                            <div class="form-group disabled_div unit_event{!!$uKy!!}">
                                <label class="text-semibold">Choose variant</label>
                                {{-- @if(in_array($uval->id,$Aunit)) disabled="disabled" @endif  --}}
                                <select name="unit[]" class="select-search unit_class req_class" id="unit_class{!!$uKy!!}">
                                    <option selected value="">Select any one</option>
                                    @if(count($unit)>0)
                                    @foreach($unit as $uky=>$uval)
                                    <option @if($uVl['id']==$uval->id) selected="selected" @endif value="{!!$uval->id!!}">{!!$uval->name!!}</option>
                                    @endforeach
                                    @endif
                                    <?php $Aunit[]  = $uVl['id']; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group disabled_div unit_event{!!$uKy!!}">
                                <label class="text-semibold">Price</label>
                                <input type="text" name="price_unit[]" class="form-control req_class" placeholder="Price" value="{!!$uVl['price']!!}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                @if($uKy >= 0)
                                <label class="text-semibold">&nbsp;</label>
                                <button type="button" class="btn btn-danger unit_minus" id="unit_minus{!!$uKy!!}" data-id={!! $uKy !!}><i class="fa fa-trash"></i></button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="text-semibold">&nbsp;</label>
                            <button type="button" class="btn btn-success btn-xs unit_edit" data-id="{!! $uKy+1 !!}"><i class="fa fa-edit"></i></button>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="row  unitdiv0" style="display:none;">
                        <div class="col-md-6 unit_event0 ">
                            <div class="form-group">
                                <label class="text-semibold">Choose variant</label>
                                <select name="unit[]" class="select-search unit_class" id="unit_class0">
                                    <option selected value="">Select any one</option>
                                    @if(count($unit)>0)
                                    @foreach($unit as $uky=>$uval)
                                    <option value="{!!$uval->id!!}">{!!$uval->name!!}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="text-semibold">Price</label>
                                <input type="text" name="price_unit[]" class="form-control" placeholder="Price">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                @if(\Auth::user()->role==1 || \Auth::user()->role==5)
                <div class="form-group">
                    <label class="text-semibold">Status</label>
                    <select name="status" id="status" class="select-search" required="">
                        <option value="" selected  disabled>select any one</option>
                        <option @if(isset($menuitem->status) && $menuitem->status=='pending') selected="" @endif value="pending">Pending</option>
                        <option @if(isset($menuitem->status) && $menuitem->status=='approved') selected="" @endif value="approved">Approved</option>
                        <option @if(isset($menuitem->status) && $menuitem->status=='cancelled') selected="" @endif value="cancelled">Cancelled</option>
                        <option @if(isset($menuitem->status) && $menuitem->status=='deleted') selected="" @endif value="deleted">Deleted</option>
                    </select>
                </div>
                <div class="form-group" @if((isset($menuitem->status) && $menuitem->status != 'cancelled') || (!isset($menuitem->status))) style="display:none;" @endif id="reason">
                    <input type="text" name="reason" id="inputreason" value="{!! (isset($menuitem->status) && ($menuitem->status == 'cancelled')) ? $menuitem->reason : '' !!}" class="form-control" placeholder="Enter the reason for reject">
                </div>
                @endif
                <div class="text-right">
                    <a href="@if(getRoleName() == 'admin'){!! url('admin/chef/'.\Request::segment(3).'/menu_item'.$url2) !!}@else{!! url(getRoleName().'/common/menu_item'.$url2) !!}@endif" class="btn btn-danger font-monserret">Cancel<i class=" icon-cancel-circle2 position-right"></i></a>
                    <button type="submit" class="btn btn-primary font-monserret">Submit<i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
    <!-- /form horizontal -->
</div>
<!-- /Content area -->
@endsection
@section('style')
<style type="text/css">
    .disabled_div{ pointer-events   : none;opacity: 0.4; }
</style>
@endsection
@section('script')

<script type="text/javascript">
    $(".styled").uniform({
        radioClass: 'choice'
    });
    var timer2      = "5:01";
    var interval    = setInterval(function() {
        var timer   = timer2.split(':');
        // by parsing integer, I avoid all extra string processing
        var minutes = parseInt(timer[0], 10);
        var seconds = parseInt(timer[1], 10);
        --seconds;
        minutes = (seconds < 0) ? --minutes : minutes;
        if (minutes < 0) clearInterval(interval);
        seconds = (seconds < 0) ? 59 : seconds;
        seconds = (seconds < 10) ? '0' + seconds : seconds;
        // minutes = (minutes < 10) ?  minutes : minutes;
        $('.countdown').html(minutes + ':' + seconds);
        timer2 = minutes + ':' + seconds;
    }, 1000);

    $(document).on('click','.unit_add',function(){
        var cnt=$(".unit_class").length;
        //console.log(cnt);  
        var rcnt=$(".req_class").length;
        console.log(rcnt); 
        var valid = false;
        if(rcnt == 0) {
            valid = true;
        } else {
            valid = true;
            $('.req_class').each(function(){
                if ($(this).val() == '' || $(this).val() == null) {
                   valid = false;
                }
            });
        }
        if (valid) {
            variants(cnt);
        }
    });

    function variants(cnt) {
        var option=$("#unit_class0").html();
        $(".unit_div").append(`
            <div class="row unitdiv`+(cnt)+`">
            <div class="col-md-5 unit_event`+(cnt)+`">
            <label class="text-semibold">Choose variant</label>
            <select name="unit[]" class="select-search unit_class req_class" id="unit_class`+(cnt)+`">
            `+option+`
            </select>
            </div>
            <div class="col-md-3 unit_event`+(cnt)+`">
            <label class="text-semibold">Price</label>
            <input type="text" name="price_unit[]" class="form-control req_class" id="unit_price" placeholder="Price">
            </div>
            <div class="col-md-1">
            <label class="text-semibold">&nbsp;</label>
            <button type="button" class="btn btn-danger btn-xs unit_minus" id="unit_minus`+(cnt)+`" data-id="`+(cnt)+`"><i class="fa fa-trash"></i></button>
            </div>
            </div>`);
        $(".unit_class").each(function(){
            $("#unit_class"+(cnt)+' option[value="'+$(this).val()+'"]').attr('disabled', true);
        })
        //$("#unit_minus"+(cnt-1)).hide();
        $("#unit_class"+(cnt)).select2();
        $(".unit_event"+(cnt-1)).addClass('disabled_div');
        if ($(".unitdiv"+(cnt-1)).find('.unit_edit').length == 0) {
            $(".unitdiv"+(cnt-1)).append(`  <div class="col-md-1">
                <label class="text-semibold">&nbsp;</label>
                <button type="button" class="btn btn-success btn-xs unit_edit" data-id="`+(cnt)+`"><i class="fa fa-edit"></i></button>
                </div>`);
        }
    }

    $(document).on('click','.unit_minus',function(e){
        e.preventDefault();
        var id=$(this).attr('data-id');
        $("#unit_minus"+(id-1)).show();
        //$(".unit_event"+(id-1)).removeClass('disabled_div');
        $(this).closest('.unitdiv'+(id)).remove();
    });

    $(document).on('click','.unit_edit',function(e){
        e.preventDefault();
        var id=$(this).attr('data-id');
        console.log(id);
        $(".unit_event"+(id-1)).removeClass('disabled_div');
        // $(".unit_event"+(id-1)).attr('disabled',false);
    });

    $(document).on('change','input[name=stock_status]',function (e) {
        if (this.value == 'instock') {
            $('.instock').show();
        } else {
            $('.instock').hide();
        }
    })

    $(document).on('change','#status',function(){
        if ( this.value == 'cancelled') {
            $('#reason').show();
            $('#inputreason').attr('required',true);
        }
        else {
            $('#reason').hide();
            $('#inputreason').removeAttr('required',true);
        }
    });

    $(document).on('click','.menutag',function(){
      if($(this).val() != ''){
        $('#tag_none').prop('checked',false);
        if($('.menutag:checked').length == 2) {
            $('.menutag:not(:checked)').each(function(){
                if($(this).attr('id') != 'tag_none'){
                    var id = $(this).attr('id');
                    $('#'+id).attr('disabled',true);
                }
            });
        } else if($('.menutag:checked').length ==1) {
            $('.menutag:not(:checked)').each(function(){
                var id = $(this).attr('id');
                $('#'+id).attr('disabled',false);
            });
        }
    } else if($(this).val() == '') {
        $('#must_try').prop('checked',false);
        $('#special').prop('checked',false);
        $('#bestsell').prop('checked',false);
        $('.menutag:not(:checked)').each(function(){
            var id = $(this).attr('id');
            $('#'+id).attr('disabled',false);
        });
    } 
    });

    $(document).ready(function(){
        if($('.menutag:checked').length == 2) {
          $('.menutag:not(:checked)').each(function(){
            if($(this).attr('id') != 'tag_none'){
                var id = $(this).attr('id');
                $('#'+id).attr('disabled',true);
            }
        });
        }
        if($('#res_type').val() == 'event') {
            $('#dish_name').text('Ticket name');
            $('#dish_image').text('Ticket image');    
            if($('#showpage').val() == 'new') {
                $('.active_page').text(' - Ticket add');
                $('.active_page2').text('Ticket add');
            } else {
                $('.active_page').text(' - Ticket edit');
                $('.active_page2').text('Ticket edit');    
            }
        }
  });
    
</script>
@endsection