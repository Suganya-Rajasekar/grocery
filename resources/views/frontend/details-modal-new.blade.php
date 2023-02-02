<!--modal start food-->
<div class="modal my-mod-1 fade" id="exampleModal{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header pinkbox">
          <h5 class="modal-title" id="exampleModalLabel">
            <span class="foodname">{{$v->name}}</span>
            <div class="d-flex">
              <div class="itembuttn" id="fnitem_<?php echo $v->id; ?>">
                <span class="add_dec_option remove_item" data-fid="{{$v->id}}" data-type="more_details" data-isad="">-</span>
                <h5 class="item-count afid_{{$v->id}}" id="afid_{{$v->id}}" data-id="{{$v->id}}">1</h5>
                <span data-fid="{{$v->id}}" class="add_dec_option add_item" data-isad="" data-id="{{$v->id}}" data-type="more_details">+</span>
              </div>
              <div class="text-theme">
                20$
              </div>
            </div>
          </h5>
          @php
          $first_unit='';
          @endphp
          @if($v->unit_detail)
  
          @php
          $first_unit=$v->unit_detail[0]->id;
          @endphp
          @endif
          <button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-myval="">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" id="form1">
            @php
            $first_unit='';
            @endphp
            @if($v->unit_detail)
  
            @php
            $first_unit=$v->unit_detail[0]->id;
            @endphp
            <div class="addon-sec over-hid">
              <div class="modalbody-head">
                <h5 class="text-black font-weight-bold">Unit</h5>
              </div>
              <div class="cont" style="line-height: 2;">
                <ul class="over-hid ord-mod">
  
                  @foreach($v->unit_detail as $ke => $unit_val)
                  <li>
                    <div class="float-left">
                      <div class=" custom-checkbox">
                        <label class="container-check" for="">{{ $unit_val->name }}
                          <input type="radio" class="custom-control-input unit" name="unit" value="{{ $unit_val->id }}" id="unit{{$v->id}}" data-id="{{$v->id}}" @if($ke==0)checked @endif>
                          <span class="checkmark"></span>
                        </label>
                      </div>
                    </div>
                    <div class="float-right">
                      <div class="foodprice">${{ $unit_val->price }}</div>
                    </div>
                  </li>
                  @endforeach
  
                </ul>
              </div>
            </div>
            @endif
            @if($v->addons)
            <div class="addon-sec over-hid">
              <div class="modalbody-head">
                <h5 class="text-black font-weight-bold">Addons</h5>
              </div>
              <div class="cont" style="line-height: 2;">
                <ul class="over-hid ord-mod">
  
                  @foreach($v->addons as $ke => $addon_val)
                  <li>
                    <div class="float-left">
                      <div class=" custom-checkbox">
                        <label class="container-check" for="">{{ $addon_val->name }}
                          <input type="checkbox" class="custom-control-input addon" name="addon" value="{{ $addon_val->id }}" id="addon{{$v->id}}" data-id="{{$v->id}}">
                          <span class="checkmarks"></span>
                        </label>
                      </div>
                    </div>
                    <div class="float-right">
                      <div class="foodprice">${{ $addon_val->price }}</div>
                    </div>
                  </li>
                  @endforeach
  
                </ul>
              </div>
            </div>
            @endif
          </form>
  
          @php
          $hours=date(('H'),strtotime($v->preparation_time));
          @endphp
          {{--$hours--}}
        </div>
        <div class="modal-footer justify-content-center">
          <div class="over-hid ord-mod-total">
            <span class="float-left text-theme font-weight-bold f-24">Total</span>
            <span class="float-right text-theme f-24">$20</span>
          </div>
          <div class="d-flex order-btn">
            @if($hours<=20)
            @php
            $today_date=date('Y-m-d');
            @endphp
              <button type="button" data-toggle="modal" data-target="#exampleModaldelivery_time{{$v->id}}"  data-dismiss="modal" class="btn btn-secondary">Order for today</button>
            @else
            @php
            $today_date='';
            @endphp
  
            <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="This dish is available only on Pre-Booking a day prior">Order for today</button>
  
            @endif
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModaldate{{$v->id}}" data-dismiss="modal">Order for future</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--modal end-->
  
  <!-- <a href="#" class="btn btn-theme btn-small bordered-small-button" data-toggle="modal" data-target="#exampleModaldate">datepicker modal</a> -->
  <!--modal start choose date-->
  <div class="modal my-mod-date fade" id="exampleModaldate{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header pinkbox">
          <h5 class="modal-title m-auto" id="exampleModalLabel">
            <span class="foodname">Order for future</span>
          </h5>
          <button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-myval="">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" id="form2">
            <div class="addon-sec over-hid">
              <div class="modalbody-head">
                <!-- <form name="myForm" action="{{ url('myurl') }}" method="POST"> -->
                  <div id="datepicker" name='datepicker_start' class="datepicker" data-id="{{$v->id}}"></div>
                  <input type="hidden" name="test" value="10">
  <!-- <input type="submit" name="submit" value="submit">  
  </form> -->
  </div>
  
  
  
  </div>
  <form action="">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary">cancel</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModaldelivery_time{{$v->id}}" data-dismiss="modal">confirm</button>
  </div>
  </div>
  </div>
  </div>
  <!--modal end choose date-->
  
  <!--modal start delivery time-->
  <div class="modal my-mod-time fade" id="exampleModaldelivery_time{{$v->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header pinkbox">
          <h5 class="modal-title m-auto" id="exampleModalLabel">
            <span class="foodname">Delivery time slots</span>
          </h5>
          <button type="button" class="close datatime{{$v->id}}" data-dismiss="modal" aria-label="Close" data-unit="{{$first_unit}}" data-id="{{$v->id}}" data-myval="">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="height: 500px;">
          <form action="" id="form3">
            @if($timeslots)
            @foreach($timeslots as $slot_k => $slot_val)
            <div class="addon-sec over-hid">
              <div class="modalbody-head">
                <h5 class="text-black font-weight-bold">{{ $slot_val->name }}</h5>
              </div>
              <div class="cont" style="line-height: 2;">
                <ul class="over-hid">
                  @if($slot_val->slots)
                  @foreach($slot_val->slots as $time_k => $time_val)
                  @php
                  $now = time();
                  $chunks = explode('-', $time_val->time_slot);
                  $open_time = strtotime($chunks[0]);
                  $close_time = strtotime($chunks[1]);
  
                  @endphp
                  {{--$open_time--}}
                  {{--$close_time--}}
                  {{--$now--}}
                  <li>
                    <div class="float-left">
                      @if((($open_time <= $now) || ($open_time >= $now)) && ($now <= $close_time))
                      @if($i=0 || $i!=2)
                      @php
                      $i=1;
                      $first_timeslot=$time_val->id;
                      @endphp
                      @endif
                      <div class=" custom-checkbox">
                        <label class="container-check" for="">{{ $time_val->time_slot }}
                          <input type="radio" class="custom-control-input timeslot" name="timeslot" value="{{ $time_val->id }}" id="timeslot{{$v->id}}" data-id="{{$v->id}}">
                          <span class="checkmark"></span>
                        </label>
                      </div>
                      @php
                      $i=2;
                      @endphp
                      @else
                      <div class=" custom-checkbox">
                        <label class="container-check"  for="">{{ $time_val->time_slot }}
                          <input type="radio" class="custom-control-input timeslot" name="timeslot" value="{{ $time_val->id }}" id="timeslot{{$v->id}}" data-id="{{$v->id}}">
                          <span class="checkmark"></span>
                        </label>
                      </div>
                      @endif
                    </div>
  
                  </li>
                  
                  @endforeach
                  @endif 
                </ul>
              </div>
            </div>
            @endforeach 
            @endif
          </form>
  
        </div>
        <div class="modal-footer">
          <div class="d-flex order-btn">
            <input type="hidden" id="addon_item_{{$v->id}}" name="addon_item" value="">
            <input type="hidden" id="future_date_{{$v->id}}" name="future_date" value="{{$today_date}}">
            <input type="hidden" id="time_slot_{{$v->id}}" name="deliveytime_slot" value="">
            <input type="hidden" id="unit_item_{{$v->id}}" name="unit_item" value="{{$first_unit}}">
            <button type="button" class="btn btn-secondary">cancel</button>
            <button type="button" class="btn btn-primary lastconfirm" data-id="{{$v->id}}" onclick="lastresult('{{$v->id}}')">Confirm</button>
          </div>
        </div>
  
      </div>
    </div>
  </div>
  <!--modal end delivery time-->