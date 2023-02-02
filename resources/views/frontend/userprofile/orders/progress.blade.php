<style>
    .modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    display: none;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
    margin-top: 200px;
}
</style>   
@if($inprocess->orders)
    <div class="accordion" id="accordionExample">
        @foreach($inprocess->orders as $pro_key => $pro_val) 
        <div class="card">
            <div class="card-header" id="headingOne">
                <button class="button-order down @if($pro_key!=0 && !(request()->ajax())) collapsed @endif" type="button" data-toggle="collapse" data-target="#inprog-{!! $pro_val->id !!}" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;">
                    <div class="hide-order fas fa-arrow-up"></div>
                    @if($pro_val->status == 'pending')
                    <div class="close-order fas fa-close" id="cancel " onclick="cancelOrder({{$pro_val->id}})"></div>
                    @endif
                    <div class="d-md-block d-none">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <p class="fnt font-montserrat"><b>Order ID: </b> <span class="d-inline-block">#{!! $pro_val->s_id !!}</span> <br>[ <b>{!!$pro_val->order_status!!}</b> ]</p>
                            </div>
                            <div class="col-5">
                                <p class="fnt font-montserrat"><b>Delivery Date/Time: </b>[ {!!  date('d M Y',strtotime($pro_val->date))!!}-{!! $pro_val->time_s !!} ]</p>
                            </div>
                            {{-- <div class="col-2">
                                <p class="fnt font-montserrat"><b>Total Items: </b><span class="round-label font-montserrat">{!! $pro_val->food_items_count !!}</span></p>
                            </div> --}}
                            <div class="col-3">
                                <p class="fnt font-montserrat"><b>Chef: </b><a href="{!! \URL::to('chef/'.$pro_val->chefinfo->id) !!}"><span>{!! $pro_val->chefinfo->name !!}</span></a></p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive-xl d-md-none">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Delivery Date/Time</th>
                                    <th>Chef</th>
                                </tr>
                            </thead>     
                            <tbody>
                                <tr>
                                    <td>#{!! $pro_val->s_id !!} <br>[ <b>{!!$pro_val->order_status!!}</b> ]</td>
                                    <td>[ {!!  date('d M Y',strtotime($pro_val->date))!!}-{!! $pro_val->time_s !!} ]</td>
                                    <td><a href="{!! \URL::to('chef/'.$pro_val->chefinfo->id) !!}"><span>{!! $pro_val->chefinfo->name !!}</span></a></td>
                                </tr>
                            </tbody>               
                        </table>
                    </div>
                </button>
            </div>
            <div id="inprog-{!! $pro_val->id !!}" class="collapse @if($pro_key==0 && !(request()->ajax()) ) show @endif" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body mb-3">
                    <div class="container-fluid">
                        <div class="row text-center progress-asw d-flex justify-content-center">
                            <div class=" prog-line @if($pro_val->status == 'pending' || $pro_val->status == 'accepted_res' || $pro_val->status == 'accepted_admin' || $pro_val->status == 'food_ready' || $pro_val->status == 'pickup_boy' || $pro_val->status == 'reached_location' || $pro_val->status == 'reached_restaurant' || $pro_val->status == 'riding' || $pro_val->status == 'accepted_boy')  progress-done  @endif">
                                <div class="prog-img progress-done">
                                    <img src="{!! asset('assets/img/tick.svg') !!}">
                                </div>
                                <span class="order-status font-montserrat"> Order placed </span>
                            </div>
                            <div class=" prog-line @if($pro_val->status == 'accepted_res' || $pro_val->status == 'accepted_admin' || $pro_val->status == 'food_ready' || $pro_val->status == 'pickup_boy' || $pro_val->status == 'reached_location' || $pro_val->status == 'reached_restaurant' || $pro_val->status == 'riding' || $pro_val->status == 'accepted_boy') progress-done  @endif">
                                <div class="prog-img  progress-done">
                                    <img src="{!! asset('assets/img/tick.svg') !!}">
                                </div>
                                <span class="order-status font-montserrat"> Accepted </span>
                            </div>
                            <div class=" prog-line @if($pro_val->status == 'food_ready' || $pro_val->status == 'completed' || $pro_val->status == 'pickup_boy' || $pro_val->status == 'reached_location' || $pro_val->status == 'reached_restaurant' || $pro_val->status == 'riding' || $pro_val->status == 'accepted_boy') progress-done  @endif">
                                <div class="prog-img progress-done">
                                    <img src="{!! asset('assets/img/pan.svg') !!}">
                                </div>
                                <span class="order-status font-montserrat"> Preparing </span>
                            </div>
                            <div class=" prog-line  @if($pro_val->status == 'pickup_boy' || $pro_val->status == 'reached_location'  || $pro_val->status == 'riding' || $pro_val->status == 'completed') progress-done @endif">
                                <div class="prog-img progress-done">
                                    <img src="{!! asset('assets/img/shipping.svg') !!}"> 
                                </div>
                                <span class="order-status font-montserrat"> On the way </span>
                            </div>
                            <div class=" prog-line  @if($pro_val->status == 'completed') progress-done @endif">
                                <div class="prog-img progress-done">
                                    <img src="{!! asset('assets/img/courier.svg') !!}">
                                </div>

                                <span class="order-status font-montserrat"> Delivered </span>
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-between profile-order-detail">
                            <div class="col-md-4 py-3 mt-2" style="border: 1px solid #ccc">
                                <span>
                                    <h5 class="font-montserrat "><b class="font-opensans ">Invoice ID :</b>{!! $pro_val->s_id !!}</h5>
                                </span>
                                <div>
                                    <b class="font-opensans ">Payment Type:</b> 
                                    <span class="font-montserrat">{!! isset($pro_val->order->payment_type) ? $pro_val->order->payment_type : '' !!}</span> </br>
                                    <b class="font-opensans ">Ordered Date:</b> 
                                    <span class="font-montserrat">{!! date('d M Y',strtotime($pro_val->created_at)) !!} </span>
                                    </br>
                                    <b class="font-opensans ">Due Date:</b>
                                    <span class="font-montserrat">{!! date('d M Y',strtotime($pro_val->date)) !!} </span>
                                    </br>
                                    <b class="font-opensans ">Total items:</b>
                                    <span class="font-montserrat">{!! $pro_val->food_items_count !!}</span>
                                </div>
                            </div>
                            <div class="col-md-4 py-3 mt-2" style="border: 1px solid #ccc">
                                <div class="">
                                    <b class="font-opensans">Delivery address : </b>
                                    <span class="font-montserrat">{!! isset($pro_val->order->get_user_address) ? $pro_val->order->get_user_address->address : '' !!}</span>
                                    @if($pro_val->rider_info != null)
                                        <b class="font-opensans">Delivery Partner : </b>
                                        <span class="font-montserrat">{!! $pro_val->rider_info->name.'-'.$pro_val->rider_info->mobile_number !!}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isset($pro_val->used_wallet_amount) && $pro_val->used_wallet_amount > 0)
                    <div class="col-12 alert alert-danger" style="height:50px;">
                        <p class="text-center" style="color: #721c24;">Wallet amount &#8377;<span class="font-weight-bold">{{ $pro_val->used_wallet_amount }}</span> used for main order id. [<span class="font-weight-bold">{{ $pro_val->m_id }}<span>]</p>
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
                            @foreach($pro_val->food_items as $item_key => $item_val)
                                <tr>
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
                                <td class="font-montserrat">&#8377; {!! number_format($pro_val->total_food_amount,2,'.','') !!}</td>
                            </tr>
                            @if($pro_val->del_charge > 0)
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Delivery charge :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($pro_val->del_charge,2,'.','') !!}</td>
                            </tr>
                            @endif
                            @if($pro_val->package_charge > 0)
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Package charge :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($pro_val->package_charge,2,'.','') !!}</td>
                            </tr>
                            @endif
                            @if($pro_val->offer_value > 0)
                             <tr>
                                <th class="font-montserrat text-right" colspan="3">Offer discount :</th>
                                <td class="font-montserrat" style="color: red;">- &#8377; {!! number_format($pro_val->offer_value,2,'.','') !!}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Total amount :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($pro_val->grand_total,2,'.','') !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="process">
    <div class="paginate"></div>
    </div>
    @if(!(\Request::has('Page')) && $inprocess->orders != '' )
    <button class="btn btn-default col-md-12 profileModule"  name="myOrders"  id="progress_orders" type="button">Load More</button>
    @endif
@else
    @if(!(\Request::has('page')))
        <div class="text-center">
            <img src="https://sustain.round.glass/wp-content/themes/sustain/assets/images/no-results.png" alt="" width="200px">
            <p class="font-montserrat">You don't have any order yet.</p>
        </div>
    @endif
@endif


<div class="modal fade" id="reasonmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cancellation Reason</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id">
        <textarea name="reason" style="height: 100px;width: 100%;" id="reason"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary reject">Cancel Order</button>
      </div>
    </div>
  </div>
</div>
