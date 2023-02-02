@extends('mail.index')
@section('content')
<div class="accordion" id="accordionExample1">
    <div class="card">
         <div  data-parent="#accordionExample">
            <div class="card-body mb-3">
                <div class="container-fluid">
                    <div class="mb-3 justify-content-between profile-order-detail" style="display:flex" >
                        {{-- <div class="py-3 mt-2" style="border: 1px solid #ccc;width: 100%; margin-right: 5px;padding: 10px;">
                            <span><h3 class="font-montserrat " style="margin: 0px;"><b class="font-opensans ">Invoice ID :</b>{!! $past_val->m_id !!}</h3></span>
                            <div>
                                <p class="font-opensans" style="color:black;"><b>Payment Type:</b> 
                                <span class="font-montserrat">{!! $past_val->order->payment_type !!}</span></p> </br>
                                <p class="font-opensans" style="color:black;"><b>Ordered Date:</b> 
                                <span class="font-montserrat">{!! date('d M Y',strtotime($past_val->created_at)) !!} </span></p>
                                </br>
                                <p class="font-opensans" style="color:black;"><b>Due Date:</b>
                                <span class="font-montserrat">{!! date('d M Y',strtotime($past_val->date)) !!} </span></p>
                                </br>
                                <p class="font-opensans" style="color:black;"><b>Total Items:</b>
                                <span class="font-montserrat">{!! $past_val->food_items_count !!}</span></p>
                            </div>
                        </div> --}}
                        <div class=" py-3 mt-2" style="border: 1px solid #ccc;width: 50%; padding: 10px;">
                            <div class="">
                                <b class="font-opensans">Invoice ID : </b>
                                <span class="font-montserrat">
                                    {!! $past_val->m_id !!}
                                </span>
                                 <div class="row align-items-center">
                                    <div class="col-3">
                                        <p class="fnt font-montserrat" style="color:black;"><b style="color:black;">Payment Type: </b>{!! $past_val->order->payment_type !!}</p>
                                    </div>
                                    <div class="col-3">
                                        <p style="color:black;"> <b class="fnt font-montserrat" style="color:black;">Ordered Date:</b> {!! date('d M Y',strtotime($past_val->created_at)) !!} </p>
                                    </div>
                                    <div class="col-3">
                                        <p style="color:black;"> <b class="fnt font-montserrat" style="color:black;">Due Date:</b> {!! date('d M Y',strtotime($past_val->date)) !!} </p>
                                    </div>
                                    <div class="col-3">
                                        <p style="color:black;"> <b class="fnt font-montserrat" style="color:black;">Total Items:</b>{!! $past_val->food_items_count !!} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" py-3 mt-2" style="border: 1px solid #ccc;width: 50%; padding: 10px;">
                            <div class="">
                                <b class="font-opensans">Delivery address : </b>
                                <span class="font-montserrat">
                                    {!! isset($past_val->order->get_user_address) ? $past_val->order->get_user_address->address : '' !!}
                                </span>
                                 <div class="row align-items-center">
                                    @if($userData->role == 3 && $past_val->order_type != "home_event_menu")
                                    <div class="col-3">
                                        <p class="fnt font-montserrat"><b>Delivery Timeslot : </b>{!! isset($past_val->timeS) ? $past_val->timeS : '' !!}</p>
                                    </div>
                                    @endif
                                    <div class="col-3">
                                        <p class="fnt font-montserrat"><b>Order </b>#{!! $past_val->m_id !!}/{{$past_val->s_id}}</p>
                                    </div>
                                    <div class="col-3">
                                        <p> <b class="fnt font-montserrat">Total Amount:</b> &#8377;{{$past_val->grand_total}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="table-responsive-xl">
                    <table class="table mb-0 table-bordered" style="border: 1px solid #dee2e6;padding: 5px;" width="100%">
                        <thead style="text-align: right;">
                            <th style="text-align: left;border-bottom: 2px solid #dee2e6;width:30%">Description</th>
                            <th style="border-bottom: 2px solid #dee2e6">Rate</th>
                            <th style="border-bottom: 2px solid #dee2e6">Quantity</th>
                            <th style="border-bottom: 2px solid #dee2e6"><span class="total-profile">Total</span></th>
                        </thead>
                        <tbody> 
                        @foreach($past_val->food_items as $item_key => $item_val)
                        @php
                        $item_val=json_decode(json_encode($item_val));
                        @endphp
                            <tr style="text-align: right;">
                                <td class="font-montserrat" style="text-align: left;white-space: nowrap;line-height: 1"> {!! $item_val->name !!} </td>
                                <td class="font-montserrat" style="white-space: nowrap;">
                                    @if(isset($item_val->discount) && $item_val->discount != 0) 
                                        <del style="color:red;">&#8377; {!! $item_val->fPrice !!}</del>
                                        <div>&#8377; {!! $item_val->fdiscount_price !!}</div>
                                    @else 
                                        &#8377; {!! $item_val->fPrice !!} 
                                    @endif
                                </td>
                                <td class="font-montserrat" style="white-space: nowrap;">{!! $item_val->quantity !!} </td>
                                <td class="font-montserrat" style="white-space: nowrap;">
                                    @if(isset($item_val->discount) && $item_val->discount != 0) 
                                        <del style="color:red;">&#8377; {!! $item_val->fPrice * $item_val->quantity !!}</del> 
                                        <div>&#8377; {!! $item_val->fdiscount_price * $item_val->quantity !!}</div>
                                    @else 
                                        &#8377; {!! ($item_val->fPrice * $item_val->quantity) !!} 
                                    @endif
                                </td>
                            </tr>
                            @if($past_val->order_type == "home_event_menu")
                                @if(!empty($item_val->meals_count) )
                                <tr class="" style="text-align: right;">
                                    <td class="font-montserrat text-muted" style="text-align: left;white-space: nowrap;color: #f55a60;">People Count:</td>
                                </tr>
                                @foreach($item_val->meals_count as $meal_key => $meal_val)
                                @foreach($meal_val as $mkey => $mval)
                                <tr class="" style="text-align: right;">
                                    <td class="font-montserrat text-muted" style="text-align: left;white-space: nowrap;">{!! $mkey !!}</td>
                                    <td></td>
                                    <td class="font-montserrat">{{ $mval }}</td>
                                </tr>
                                @endforeach
                                @endforeach
                                @endif

                                @if(!empty($item_val->preferences) )
                                <tr class="" style="text-align: right;">
                                    <td class="font-montserrat text-muted" style="text-align: left;white-space: nowrap;color: #f55a60;">preferences</td>
                                </tr>
                                @foreach($item_val->preferences as $pref_key => $pref_val)
                                <tr class="" style="text-align: right;">
                                    <td class="font-montserrat text-muted" style="text-align: left;white-space: nowrap;">{!! $pref_val->name !!}</td>
                                </tr>
                                @endforeach
                                @endif

                                @if(!empty($item_val->theme) )
                                <tr class="" style="text-align: right;">
                                    <td class="font-montserrat text-muted" style="text-align: left;white-space: nowrap;color: #f55a60;">Themes</td>
                                </tr>
                                @foreach($item_val->theme as $theme_key => $theme_val)
                                <tr class="" style="text-align: right;">
                                    <td class="font-montserrat text-muted" style="text-align: left;white-space: nowrap;">{!! $theme_val->name !!}</td>
                                    <td class="font-montserrat" style="white-space: nowrap;">&#8377; {!! $theme_val->amount !!}</td>
                                    <td></td>
                                    <td class="font-montserrat" style="white-space: nowrap;" colspan="2">&#8377; {!! number_format($theme_val->amount,2,'.','') !!}</td>
                                </tr>
                                @endforeach
                                @endif

                                @if(!empty($item_val->addon) )
                                 <tr class="" style="text-align: right;">
                                    <td class="font-montserrat text-muted" style="text-align: left;white-space: nowrap;color: #f55a60;">Add ons</td>
                                 </tr>
                                @endif
                            @endif


                            @if(!empty($item_val->addon) )
                            @foreach($item_val->addon as $addon_key => $addon_val)
                            <tr class="" style="text-align: right;">
                                <td class="font-montserrat text-muted" style="text-align: left;white-space: nowrap;">{!! $addon_val->name !!}</td>
                                <td class="font-montserrat" style="white-space: nowrap;">&#8377; {!! $addon_val->price !!}</td>
                                {{-- <td class="font-montserrat">1</td> --}}
                                <td class="font-montserrat" style="white-space: nowrap;" colspan="2">&#8377; {!! number_format($addon_val->price,2,'.','') !!}</td>
                            </tr>
                            @endforeach
                            @endif
                        @endforeach
                        <tr>
                            <th class="font-montserrat text-right" style="text-align: right;white-space: nowrap;" colspan="3">Total Amount :</th>
                            <td class="font-montserrat" style="text-align: right;white-space: nowrap;">&#8377; {!! number_format($past_val->total_food_amount,2,'.','') !!}</td>
                        </tr>
                        @if($past_val->del_charge > 0)
                        <tr>
                            <th class="font-montserrat text-right" style="text-align: right;white-space: nowrap;" colspan="3">Delivery Charge :</th>
                            <td class="font-montserrat" style="text-align: right; white-space: nowrap;">&#8377; {!! number_format($past_val->del_charge,2,'.','') !!}</td>
                        </tr>
                        @endif
                        @if($past_val->package_charge > 0)
                        <tr>
                            <th class="font-montserrat text-right" style="text-align: right;white-space: nowrap;" colspan="3">Package charge :</th>
                            <td class="font-montserrat" style="text-align: right;white-space: nowrap;">&#8377; {!! number_format($past_val->package_charge,2,'.','') !!}</td>
                        </tr>
                        @endif
                        @if($past_val->tax > 0)
                        <tr>
                            <th class="font-montserrat text-right" style="text-align: right;white-space: nowrap;" colspan="3">Tax :</th>
                            <td class="font-montserrat" style="text-align: right;white-space: nowrap;">&#8377; {!! number_format($past_val->tax_amount,2,'.','') !!}</td>
                        </tr>
                        @endif
                        @if($past_val->offer_value > 0)
                        <tr>
                            <th class="font-montserrat text-right" style="text-align: right;white-space: nowrap;" colspan="3">Offer discount :</th>
                            <td class="font-montserrat" style="text-align: right;white-space: nowrap;">- &#8377;   {!! number_format($past_val->offer_value,2,'.','') !!}</td>

                        </tr>
                        @endif
                        <tr>
                            <th class="font-montserrat text-right" style="text-align: right;white-space: nowrap;" colspan="3">Grand Total :</th>
                            <td class="font-montserrat" style="text-align: right;white-space: nowrap;">&#8377; {!! number_format($past_val->grand_total,2,'.','') !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    
</div>
@stop
