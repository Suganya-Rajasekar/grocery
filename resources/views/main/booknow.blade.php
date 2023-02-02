@extends('main.app')

@section('content')
<section class="banner">
    <div class="container-fluid">
    <div class="row p-3">
        <div class="col-md-6">
            <div class="ban-content ban-content-flex">
               <h1 class=" my-5">Book a service</h1>
           </div>
              
        </div>
        <div class="col-md-6">
            <div class="bookimg">
                <img src="{!! asset('assets/front/img/support.png') !!}">
            </div>
        </div>
    </div>
    </div><!-- container-fluid -->
</section>
<section class="booking-form">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mt-5 ">
                <div class="form-box">
                    <form id="book_register_form" >
                    <div class="serviceform">
                        <div class="sec">
                            <h2 class="title_form">{{ $category->name }} Booking</h2>
                            <p>You are in a safe hands, book a cleanig service with us under 60 seconds</p>
                            <p>kindly the the below forn to get started</p>
                        </div>
                        <hr>
                        <input type="hidden" name="category" value="{!! $category->id !!}">
                        @csrf
                        <div class="sec">
                            <div class="step1">
                                <h5 class="book-title">Step1: choose a service</h5>
                                <ul>
                                    @foreach($Services as $service)
                                        <li>
                                            <span class="text-dark">{{ $service->name }}:</span><span class="text-muted">{{ $service->description }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <hr>
                        <div class="sec">
                            <div class="inputs input-height">
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                <select name="service" id="service" required="" class="form-control">
                                    @foreach($Services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                               </select>
                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                <select name="bedroom" id="bedroom" required="" class="form-control">
                                    @for($i = 1;$i<=3;$i++)
                                        <option value="{{ $i }}">{{ $i }} Bedrooms</option>
                                    @endfor
                                </select>
                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                         <div class="form-group">
                                <select name="bathroom" id="bathroom" class="form-control">
                                    @for($i = 1;$i<=3;$i++)
                                        <option value="{{ $i }}">{{ $i }} Bathrooms</option>
                                    @endfor
                                </select>
                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="sec">
                            <div class="step2">
                                <h5 class="book-title">Step2: Select addon</h5>
                                <p>Select an addon service you need</p>
                                <p>note: </p>
                                <div class="row">
                                    @foreach ($Addons as $addon)
                                    <div class="addon-service col-md-4 col-lg-3">
                                        <input type="checkbox" name="addon[]" value="{{ $addon['id'] }}">
                                        <div class="img ">{{-- class for blue bg when check..... "inputchecked" --}}
                                            <img src="{{ $addon->image_src }}" alt="">
                                        </div>
                                        <p>{{ $addon['name'] }} {{ $addon['price'] }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="sec">
                            <div class="step3 input-height">
                                <h5 class="book-title">Step2: Kindly pick date and time</h5>
                                <p class="text-muted">Pick a date and time based on availability</p>
                                <p><span>Note: </span><span class="text-muted"></span></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                  </div>
                                  <input type="text" class="form-control date_picker" autocomplete="off" name="date" id="date" required="">
                                </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-clock"></i></span>
                                  </div>
                                  <input type="text" class="form-control" list="timeList" autocomplete="off" name="time" required="">
                                <datalist id="timeList">
                                    @foreach($Time as $time)
                                    <option value="{!! $time->name !!}"></option>
                                    @endforeach
                                </datalist>
                                </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="sec">
                            <div class="step4">
                                <h5 class="book-title">Step 4: Frequent Customer</h5>
                                <p class="text-muted">Pick a date and time based on availability</p>
                                <p><span>Note: </span><span class="text-muted"></span></p>
                                <div class="row frequent-cust-inputs">
                                    @foreach ($SubscriptionPlans as $subs)
                                    <div class="col-lg-4 col-md-6 col-sm-12"><label class="">{{-- class for blue bg when check..... "inputchecked" --}}
                                        <input type="radio" value="{{ $subs->id }}" name="period" placeholder="">{{ $subs->showable_text }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="sec">
                            <div class="step5 input-height">
                                <h5 class="book-title">Step5: Address</h5>
                                <p>Kindly enter address for the service</p>
                                <p>note:</p>
                                <div class="row">
                                    <div class="col-md-8">
                                         <input type="text" class="form-control" name="address" id="address" required="" placeholder="Address">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="door_no" id="door_no" required="" placeholder="Door No">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="city" id="city" required="" placeholder="City">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="state" id="state" required="" placeholder="State">
                                    </div>
                                    <div class="col-md-4">
                                        <input min="0" type="number" class="form-control" name="zipcode" id="zipcode" placeholder="Zip Code">
                                    </div>
                                </div>
                            </div>
                        </div><!-- sec -->
                        <hr>
                        <div class="sec">
                            <div class="step6 input-height">
                                <h5 class="book-title">Step5: Contact details</h5>
                                <p>Kindly enter the  contact details</p>
                                <p>note:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="first_name" id="first_name" value="{{\Auth::user()->name }}" required="" placeholder="Enter your first name*">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="last_name" id="last_name" required="" placeholder="Enter your last name*">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="{{\Auth::user()->phone_number }}" required="" placeholder="Enter your phone number* ">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="email" id="email" required="" value="{{\Auth::user()->email }}" placeholder="Enter your email id*">
                                    </div>

                                    <div class="col-md-12">
                                        <hr>
                                        <h5 class="book-title">Any Comments?</h5>
                                    <div class="form-group">
                                
                                <textarea class="form-control" name="comments" id="comments" required="" cols="100" rows="9"></textarea>
                                    </div>
                                </div>
                            
                                
                                    
                            
                                </div>
                            </div>
                        </div><!-- sec 6 -->
                        <hr>
                        <div class="sec">
                            <div class="step7 input-height">
                                <h5 class="book-title">Step7: Payment</h5>
                                <p>Kindly enter the  contact details</p>
                                <p>note:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                         <input type="text" maxlength="19" class="form-control" name="card_no" id="card_no" required="" placeholder="Enter your card number*">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" autocomplete="off" name="card_date" id="card_date" required="" placeholder="mm / dd / yyy*">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" maxlength="3" minlength="3" class="form-control" autocomplete="off" name="cvv" id="cvv" required="" placeholder="Enter your CVV">
                                    </div>
                                </div>
                            </div>
                        </div><!-- sec 7 -->
                        <hr>
                        <div class="sec">
                        <button class="btn_submit btn btn-theme btn-full border-radius-btn " name="submit" type="submit" id="submit">Book Now</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="col-md-4 mt-5 SideBar" style="display: none">
                <div class="form-box">
                    <div class="booksummary">
                        @if(!\Auth())
                        <p class="summary-conts text-muted"><a href="#" class="text-theme">Login</a> or <a href="#" class="text-theme">Register</a> with us</p> @endif()
                        <p class="summary-conts font-weight-bold text-white btn-second">Booking summary</p>
                        <ul class="summary-list text-muted my-5">
                            
                        </ul>
                        <hr>
                        <p class="summary-conts total-price text-muted">Total : <span></span></p>
                    </div>
                </div>
            </div>
        </div>
  </div>
</section>
@endsection

