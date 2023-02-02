@if($past_order->orders)
    <div class="accordion" id="accordionExample">
        @foreach($past_order->orders as $past_key => $past_val)
        <div class="card">
            <div class="card-header" id="headingOne">
                <button class="button-order down @if($past_key !=0 && !(request()->ajax())) collapsed @endif" type="button" data-toggle="collapse" data-target="#collapseTwo-{{$past_val->id}}" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;">
                    <div class="hide-order fas fa-arrow-up"></div>
                   {{--  <div class="close-order fas fa-close" onclick='cancelOrder("{{$past_val->id}}")'></div> --}}
                    <div class="d-md-block d-none">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <p class="fnt font-montserrat">Order <span class="d-inline-block">#{!! $past_val->m_id !!}/{{$past_val->s_id}} </span><br>[ <b>{!!$past_val->order_status!!}</b> ] </p>
                            </div>
                            <div class="col-3">
                                <p class="fnt font-montserrat">{{date('d M Y',strtotime($past_val->date))}}</p>
                            </div>
                            <div class="col-3">
                                <p class="fnt font-montserrat" >{{ count($past_val->food_items)}} items</p>
                            </div>
                            <div class="col-3">
                                <p><b class="fnt font-montserrat">&#8377;{{$past_val->grand_total}}</b></p>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
            <div id="collapseTwo-{{$past_val->id}}" class="collapse @if($past_key==0  && !(request()->ajax()) ) show @endif" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body mb-3">
                    <div class="container-fluid">
                        <div class="row mb-3 justify-content-between profile-order-detail">
                            <div class="col-md-4 py-3 mt-2" style="border: 1px solid #ccc">
                                <span><h5 class="font-montserrat "><b class="font-opensans ">Invoice ID :</b>{!! $past_val->m_id !!}</h5></span>
                                <div>
                                    <b class="font-opensans ">Payment Type:</b> 
                                    <span class="font-montserrat">{!! $past_val->order->payment_type !!}</span> </br>
                                    <b class="font-opensans ">Ordered Date:</b> 
                                    <span class="font-montserrat">{!! date('d M Y',strtotime($past_val->created_at)) !!} </span>
                                    </br>
                                    <b class="font-opensans ">Due Date:</b>
                                    <span class="font-montserrat">{!! date('d M Y',strtotime($past_val->date)) !!} </span>
                                    </br>
                                    <b class="font-opensans ">Total items:</b>
                                    <span class="font-montserrat">{!! $past_val->food_items_count !!}</span>
                                </div>
                            </div> 
                            <div class="col-md-4 py-3 mt-2" style="border: 1px solid #ccc">
                                <div class="">
                                    <b class="font-opensans">Delivery address : </b>
                                    <span class="font-montserrat">{!! isset($past_val->order->get_user_address) ? $past_val->order->get_user_address->address : '' !!}</span>
                                    @if($past_val->rider_info != null)
                                    <div>
                                        <b class="font-opensans">Delivery Partner : </b>
                                    </div>
                                    <p class="font-montserrat m-0"><b class="font-opensans">Name : </b>{!! $past_val->rider_info->name !!}</p>
                                    <p class="font-montserrat m-0"><b class="font-opensans">Phone Number : </b>{!! $past_val->rider_info->mobile_number !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                @if(isset($past_val->used_wallet_amount) && $past_val->used_wallet_amount > 0)
                    <div class="col-12 alert alert-danger" style="height:50px;">
                        <p class="text-center" style="color: #721c24;">Wallet amount &#8377;<span class="font-weight-bold">{{ $past_val->used_wallet_amount }}</span> used for main order id. [<span class="font-weight-bold">{{ $past_val->m_id }}<span>]</p>
                    </div>
                @endif
                <div class="table-responsive-xl">
                    <table class="table mb-0 table-bordered">
                        <thead>
                            <th>Description</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th><span class="total-profile">Total</span></th>
                        </thead>
                        <tbody>
                            @foreach($past_val->food_items as $item_key => $item_val)
                            <tr>
                                {{-- <?php dd($item_val);?> --}}
                                <td class="font-montserrat">{!! $item_val->name !!}</td>
                               <td class="font-montserrat">@if(isset($item_val->discount) && $item_val->discount != 0) <del style="color:red;">&#8377; {!! $item_val->fPrice !!}</del> @else &#8377; {!! $item_val->fPrice !!} @endif
                                    @if(isset($item_val->discount) && $item_val->discount != 0)
                                    <div>&#8377; {!! $item_val->fdiscount_price !!}</div>
                                    @endif
                                    </td>
                                <td class="font-montserrat">{!! $item_val->quantity !!}</td>
                                 <td class="font-montserrat">@if(isset($item_val->discount) && $item_val->discount != 0) <del style="color:red;">&#8377; {!! $item_val->fPrice * $item_val->quantity !!}</del> @else &#8377; {!! ($item_val->fPrice * $item_val->quantity) !!} @endif
                                     @if(isset($item_val->discount) && $item_val->discount != 0)
                                    <div>&#8377; {!! $item_val->price !!}</div>
                                     @endif   
                                    </td>
                            </tr>
                            @if($item_val->addon)
                            @foreach($item_val->addon as $addon_key => $addon_val)
                            <tr class="">
                                <td class="font-montserrat text-muted">{!! $addon_val->name !!}</td>
                                <td class="font-montserrat" colspan="2">&#8377; {!! $addon_val->price !!}</td>
                                {{-- <td class="font-montserrat">1</td> --}}
                                <td class="font-montserrat">&#8377; {!! $addon_val->price !!}</td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Total food cost :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($past_val->total_food_amount,2,'.','') !!}</td>
                            </tr>
                            @if($past_val->del_charge > 0)
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Delivery charge :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($past_val->del_charge,2,'.','') !!}</td>
                            </tr>
                            @endif
                            @if($past_val->package_charge > 0)
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Package charge :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($past_val->package_charge,2,'.','') !!}</td>
                            </tr>
                            @endif
                            @if($past_val->offer_value > 0)
                             <tr>
                                <th class="font-montserrat text-right" colspan="3">Offer discount :</th>
                                <td class="font-montserrat" style="color: red;">- &#8377; {!! number_format($past_val->offer_value,2,'.','') !!}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Total amount :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($past_val->grand_total,2,'.','') !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if($past_val->status == 'completed')
                <?php ;?>
                <div class="review-btn float-right">
                    <button class="btn btn-theme" onclick="openorder_review('{!! $past_val->id !!}')"><i class="fas fa-comment"></i>@if(!empty($past_val->reviewinfo->id)) Edit Reviews @else Reviews @endif</button>
                </div>
                @if(!empty($past_val->reviewinfo->id))
                <div class="review-btn float-right" style="margin-right:10px;">
                    <button class="btn btn-theme review_remove" data-reviewid="{{ $past_val->reviewinfo->id }}"><i class="fas fa-trash"></i>Delete Reviews</button>
                </div>
                @endif
                @endif
            </div>    
        </div>
        @endforeach
    </div>
<!-- The Modal -->
<div class="modal" id="reviews" style="margin-top: 0px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body contentbox">
            </div>
        </div>
    </div>
</div>
<div class="past">
<div class="paginate"></div>
@if(!(\Request::has('Page')) && $past_order->orders != '' )
<button class="btn btn-default col-md-12 profileModule"  name="myOrders" id="past_orders" type="button">Load More</button>
@endif
</div>
@else
@if(!(\Request::has('page')))
<div class="text-center">
    <img src="https://sustain.round.glass/wp-content/themes/sustain/assets/images/no-results.png" alt="" width="200px">
    <p class="font-montserrat">You have not placed any orders in the past.</p>
</div>
@endif
@endif
