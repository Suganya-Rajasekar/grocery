@extends('main.app')

@section('content')
<section class="">
    <div class="userss paymentgateway mt-5">
        <div class="container-fluid">
             <div class="row ">
                 <div class="px-3">
                <h5 class="font-weight-bold f_30">Payment Gateways</h5>
                <div><p class="f_16">Connect your payment gateways to start managing your subscription plans</p></div>
            </div>
            </div>
            <div class="row mt-5 mb-4">
               <div class="col-lg-3 col-md-6 col-sm-6 my-3">
                   <div class="box-div text-center">
                    <div class="pay_img"><img src="{!! asset('assets/front/img/stripe_logo.png') !!}"></div>
                    @if($AllPayments->stripe ?? null == null)
                       <a href="connect/stripe" class="btn pay_btn bg-dark text-white">Add Account</a>
                    @else
                       <a href="portal/stripe" class="btn pay_btn bg-dark text-white">Manage Account</a>
                    @endif
                   </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-6 my-3">
                   <div class="box-div text-center">
                    <div class="pay_img"><img src="{!! asset('assets/front/img/logo_dark.svg') !!}"></div>
                     @if($AllPayments->paypal ?? null == null)
                       <a href="connect/paypal" class="btn pay_btn bg-dark text-white">Add Account</a>
                    @else
                       <a href="portal/stripe" class="btn pay_btn bg-dark text-white">Manage Account</a>
                    @endif
                   </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-6 my-3">
                   <div class="box-div text-center">
                    <div class="pay_img"><img src="{!! asset('assets/front/img/logo_dark.svg') !!}"></div>
                       <button class="btn pay_btn bg_blue text-white">Add Payment Gateway</button>
                   </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-6 my-3">
                   <div class="box-div text-center">
                    <div class="pay_img"><img src="{!! asset('assets/front/img/logo_dark.svg') !!}"></div>
                       <button class="btn pay_btn bg_blue text-white">Add Payment Gateway</button>
                   </div>
               </div>
        </div>
     
        </div>
    </div>
</section>

@endsection

