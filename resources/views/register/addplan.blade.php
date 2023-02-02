@extends('main.app') @section('content')
<section class="">
  <div class="userss user_newsubscriptionplans mt-5">
    <div class="container-fluid">
      <div class="row ">
        <div class="newsub">
          <h5 class="font-weight-bold f_22"><span class=" fa fa-arrow-left blue pr-4"></span>Add New Subscription plan</h5>
          <div class=""><span class="f_14 pr-3">Listing</span><span class="f_14 pr-2">Add Subscription Plan</span></div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-md-12 box-div">
          <div class="row mb-2">
            <div class="col-md-12">
              <p style="color:#192651"> kindly fill out all fields</p>
            </div>
          </div>
          <form id="user_subscription_form">
            @if ($Subsc->id ?? '')
                <input type="hidden" id="id" name="id" value="{{ $Subsc->id ?? '' }}">
            @endif
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="company_name">Company Business Name</label>
                  <input type="text" name="company_name" id="company_name" value="{{ $Subsc->company_name ?? '' }}" class="form-control"> </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="website">Website url</label>
                  <input type="url" name="website" id="website" value="{{ $Subsc->website ?? '' }}" class="form-control"> </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="plan_name">Name of the plan</label>
                  <input type="text" name="plan_name" id="plan_name" value="{{ $Subsc->plan_name ?? '' }}" class="form-control"> </div>
              </div>
              <div class="col-md-6">
                <label for="currency">Cost of the plan</label>
                <di class="row">
                  <div class="col-md-5 pr-0">
                    <div class="form-group">
                      <select name="currency" id="currency" class="custom-select form-control custom-select-lg mb-3">
                        @php
                        $aCurrencies  = getCurrencies();
                        @endphp
                        <option disabled="">Select a currency</option>
                        @foreach($aCurrencies as $curr)
                        <option 
                        @if(isset($Subsc->currency)) 
                          @if($curr->id == $Subsc->currency) selected 
                          @endif 
                        @endif  value="{{ $curr->id ?? '' }}">{{ $curr->currency_name ?? '' }} ({{ $curr->symbol ?? '' }})</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <input type="text" name="price" id="price" value="{{ $Subsc->price ?? '' }}" class="form-control"> </div>
                  </div>
                </di>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="last_paid_date">Last paid date</label>
                  <input type="date" name="last_paid_date" id="last_paid_date" value="{{ $Subsc->last_paid_date ?? '' }}" class="form-control"> </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="plan_expiry_date">Expiry date of the plan</label>
                  <input type="date" name="plan_expiry_date" id="plan_expiry_date" value="{{ $Subsc->plan_expiry_date ?? '' }}" class="form-control"> </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="interval_days">Interval (In days)</label>
                  <input type="number" name="interval_days" id="interval_days" value="{{ $Subsc->interval_days ?? '' }}" class="form-control"> </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="payment_type">Payment Type</label>
                  <select name="payment_type" id="payment_type" value="" class="form-control">
                    <option disabled="">Select a payment type</option>
                    @php
                        $aPayments  = getPayments();
                    @endphp
                    @foreach($aPayments as $pay)
                    <option
                     @if(isset($Subsc->currency)) 
                     @if($pay->name == $Subsc->payment_type) selected @endif @endif  value="{{ $pay->name ?? '' }}">{{ $pay->name ?? '' }}</option>
                    @endforeach
                  </select></div>
              </div>
              <div class="col-md-12 text-center my-5 ">
                <button type="button" class="btn graybtn_h view_more_btn subsc_cancel_btn">Cancel</button>
                <button class="btn btn_b_radius bluebtn_h bg_blue px-5">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section> 

<div class="modal fade" id="changedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Attention!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body"> There are unsaved data in the screen.Do you still wish to go back? </div>
            <div class="modal-footer"> <a class="btn btn-primary" onClick="location.replace('<?php echo URL::to('/').'/'; ?>subscriptions')">Yes</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> No </button>
            </div>
        </div>
    </div>
</div>
@endsection