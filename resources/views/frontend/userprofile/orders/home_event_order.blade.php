@if($home_events->orders)
<div class="tab-content profile-asw-myorder-tabs-content">
    <div class="accordion" id="accordionExample">
        @foreach($home_events->orders as $event_key => $event_val)
        <div class="card">
            <div class="card-header d-flex" id="headingOne">
                <button class="button-order down" type="button" data-toggle="collapse" data-target="#collapseTwo-{{$event_val->id}}" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;">
                    <div class="hide-order fas fa-arrow-up"></div>
                    <div class="d-md-block d-none">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <p class="fnt font-montserrat"><b>Booking id</b><span class="d-inline-block">#{!! $event_val->m_id !!}/{{$event_val->s_id}} </span><br></p>
                            </div>
                            <div class="col-3">
                            </div>
                            <div class="col-3">
                                <p class="fnt font-montserrat"><b>Event date : </b> {{ date('d-m-Y',strtotime($event_val->date)) }}</p> 
                            </div>
                            <div class="col-3">
                                <p><b class="fnt font-montserrat">&#8377;{{$event_val->grand_total}}</b></p>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
            <div id="collapseTwo-{{$event_val->id}}" class="collapse @if($event_key==0  && !(request()->ajax()) ) show @endif" aria-labelledby="headingOne" data-parent="#accordionExample"> 
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
                            @if(isset($item_val->meals_count) && !empty($item_val->meals_count))
                            <th class="font-montserrat" colspan="3" style="color: #f55a60;">People Count :</th>
                            @foreach($item_val->meals_count[0] as $mkey => $mval)
                            <tr>
                                <td class="font-montserrat" colspan="2">{{ $mkey }}</td>
                                <td class="font-montserrat">{{ $mval }}</td>
                            </tr>
                            @endforeach
                            @endif


                            @if(isset($item_val->preferences) && !empty($item_val->preferences))
                            <th class="font-montserrat" colspan="3" style="color: #f55a60;">Preferences :</th>
                            @foreach($item_val->preferences as $pkey => $pval)
                            <tr>
                                <td class="font-montserrat" colspan="3">{{ $pval->name }}</td>
                            </tr>
                            @endforeach
                            @endif

                            @if(isset($item_val->theme) && !empty($item_val->theme))
                            <th class="font-montserrat" colspan="3" style="color: #f55a60;">Theme :</th>
                            <tr>
                                <td class="font-montserrat">{{ $item_val->theme[0]->name }}</td>
                                <td class="font-montserrat" colspan="2">{{ $item_val->theme[0]->amount }}</td>
                                <td class="font-montserrat">{{ $item_val->theme[0]->amount }}</td>

                            </tr>
                            @endif

                            @if($item_val->addon)
                            <th class="font-montserrat" colspan="3" style="color: #f55a60;">Addons :</th>
                            @foreach($item_val->addon as $addon_key => $addon_val)
                            <tr class="">
                                <td class="font-montserrat">{!! $addon_val->name !!}</td>
                                <td class="font-montserrat" colspan="2">&#8377; {!! $addon_val->price !!}</td>
                                <td class="font-montserrat">&#8377; {!! $addon_val->price !!}</td>
                            </tr>
                            @endforeach
                            @endif

                            @endforeach

                            <tr>
                                <th class="font-montserrat text-right" colspan="3">Total Menu cost :</th>
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
<div class="home_event">
<div class="paginate"></div>
@if(!(\Request::has('Page')) && $home_events->orders != '' )
<button class="btn btn-default col-md-12 profileModule"  name="home_events" id="home_events" type="button">Load More</button>
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
