@if($events->orders)
<div class="tab-content profile-asw-myorder-tabs-content">
    <div class="accordion" id="accordionExample">
        @foreach($events->orders as $event_key => $event_val)
        <div class="card">
            <div class="card-header d-flex" id="headingOne">
                <button class="button-order down" type="button" data-toggle="collapse" data-target="#collapseTwo-{{$event_val->id}}" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;">
                    <div class="hide-order fas fa-arrow-up"></div>
                   {{--  <div class="close-order fas fa-close" onclick='cancelOrder("{{$event_val->id}}")'></div> --}}
                    <div class="d-md-block d-none">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <p class="fnt font-montserrat">Booking id <span class="d-inline-block">#{!! $event_val->m_id !!}/{{$event_val->s_id}} </span><br></p>
                            </div>
                            <div class="col-3">
                            </div>
                            <div class="col-3">
                                <?php  $event_date_time = explode(' ',$event_val->chefinfo->event_time,2); ?>
                                <p class="fnt font-montserrat"><b>Event date & time :</b></p> 
                                <p class="fnt font-montserrat">{{ date('d-m-Y',strtotime($event_date_time[0])).' / '.$event_date_time[1]}}</p>
                            </div>
                            <div class="col-3">
                                <p><b class="fnt font-montserrat">&#8377;{{$event_val->grand_total}}</b></p>
                            </div>
                        </div>
                    </div>
                </button>
                {{-- <span id="info" class="my-auto" data-id="{{$event_val->id}}" data-toggle="modal" data-target="#event_info{{$event_val->id}}"><i class="fa fa-info-circle text-primary eventinfo_icon" aria-hidden="true"></i></span> --}}
            </div>
            <div id="collapseTwo-{{$event_val->id}}" class="collapse @if($event_key==0  && !(request()->ajax()) ) show @endif" aria-labelledby="headingOne" data-parent="#accordionExample"> 
                <div class="card-body mb-3">
                    <div style="text-align: end;">
                        <button class="btn btn-info" data-id="{{$event_val->id}}" data-toggle="modal" data-target="#event_info{{$event_val->id}}">Event Ticket</button>
                    </div>
                    <div class="container-fluid">
                        <div class="row mb-3 justify-content-between profile-order-detail">
                            <div class="col-md-12 py-3 mt-2" style="border: 1px solid #ccc">
                                <span><b class="font-opensans">Location :</b></span>
                                <div>
                                    <p class="font-montserrat" >{{ $event_val->chefinfo->event_location }}</p>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div> 
                <div class="table-responsive-xl">
                    <table class="table mb-0 table-bordered">
                        <thead>
                            <th>Description</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th><span class="total-profile">Total</span></th>
                        </thead>
                        <tbody>
                            @foreach($event_val->food_items as $item_key => $item_val)
                            <tr>
                                <td class="font-montserrat">{!! $item_val->name !!}</td>
                                <td class="font-montserrat">&#8377; {!! $item_val->fPrice !!}</td>
                                <td class="font-montserrat">{!! $item_val->quantity !!}</td>
                                <td class="font-montserrat">&#8377; {!! ($item_val->fPrice * $item_val->quantity) !!}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Total Ticket cost :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($event_val->total_food_amount,2,'.','') !!}</td>
                            </tr>
                            @if($event_val->offer_value > 0)
                             <tr>
                                <th class="font-montserrat text-right" colspan="3">Offer discount :</th>
                                <td class="font-montserrat" style="color: red;">- &#8377; {!! number_format($event_val->offer_value,2,'.','') !!}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Total amount :</th>
                                <td class="font-montserrat">&#8377; {!! number_format($event_val->grand_total,2,'.','') !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- @if($event_val->status == 'completed')
                <?php ;?>
                <div class="review-btn float-right">
                    <button class="btn btn-theme" onclick="openorder_review('{!! $event_val->id !!}')"><i class="fas fa-comment"></i>@if(!empty($event_val->reviewinfo->id)) Edit Reviews @else Reviews @endif</button>
                </div>
                @if(!empty($event_val->reviewinfo->id))
                <div class="review-btn float-right" style="margin-right:10px;">
                    <button class="btn btn-theme review_remove" data-reviewid="{{ $event_val->reviewinfo->id }}"><i class="fas fa-trash"></i>Delete Reviews</button>
                </div>
                @endif
                @endif --}}
            </div>    
        </div>
        <!-- Event info and QR code modal start -->  
        <div class="modal fade event" id="event_info{{$event_val->id}}" style="margin-top: 0px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <div class="col-4">
                            <img src="{{ url('images/100x100.png') }}" id="logo">
                            <span id="logo-name">Knosh</span>
                        </div>
                        <div class="col-8">
                            <h4 class="event-info-date">{{ $event_val->chefinfo->event_time }}</h4>
                        </div>
                    </div>
                    <div class="modal-body contentbox">
                        <div class="event_name mt-4">
                            <h5 class="event-text d_inline">Event name : </h4>
                            <h5 class="event-text d_inline">{{ $event_val->chefinfo->name }}</h4>
                        </div> 
                        <div class="event_order_no mt-4">
                            <h5 class="event-text d_inline">Order Number : </h4>
                            <h5 class="event-text d_inline">{{ $event_val->s_id }}</h4>
                        </div>
                        <div class="qrcode d-flex justify-content-center">
                            <div class="qr-div">
                             {!! QrCode::size(160)->generate("Order id :".$event_val->s_id."\nBuyer Name :".$event_val->userinfo->name."\nBuyer Mobile :".$event_val->userinfo->mobile."\nEvent Name :".$event_val->chefinfo->name."\nEvent date&time :".$event_val->chefinfo->event_time."\nTicket Quantity :".$event_val->ticket_total_count."\nType of Ticket :".$event_val->ticket_types_count) !!}
                         </div>
                     </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Event info and QR code modal end -->  
        @endforeach
    </div>
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
@if(!(\Request::has('Page')) && $events->orders != '' )
<button class="btn btn-default col-md-12 profileModule"  name="myOrders" id="past_orders" type="button">Load More</button>
@endif
</div>
@else
@if(!(\Request::has('page')))
<div class="text-center">
    <img src="https://sustain.round.glass/wp-content/themes/sustain/assets/images/no-results.png" alt="" width="200px">
    <p class="font-montserrat">You have not booked any events.</p>
</div>
@endif
@endif
