@extends('main.app')
@section('content')
<section class="topsec"></section>
<section class="success-pay">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-8 mx-auto">
                <div class="cont-pay">
                    <div class="succ-img">
                        <img src="assets/img/tqtick.svg" alt="">
                    </div>
                    <h3 class="font-weight-bold mb-4 mt-2">Order Placed</h3>
                    @if($order_type == 'menuitem')
                    <p class="py-4">We have mailed you the details of your order.You can also track your order online .Go to “My Orders“ under “Profile“ section.</p>
                    @else
                    <p class="py-4">We have mailed you the details of your order.You can also track your order online .Go to “My Events“ under “Profile“ section.</p>
                    @endif
                    <div class="orderno">
                        <p>Your order number is</p>
                        <p class="pt-3 text-theme">{{ $recentOrderId }}</p>
                    </div>
                </div>
                <div class="thank">
                    <!-- <div class="smiley mb-3">
                    {{-- <img src="assets/front/img/succ.png" alt=""> --}}
                    <img src="assets/img/emoji.svg" width="60px">
                    </div> -->
                    <h3 class="font-weight-bold">Let's knosh</h3>
                    <div class="submit-btn">
                        <a href="@if($order_type == "menuitem") {{ url('user/dashboard/myOrders?section=in_progress') }} @else {{ url('user/dashboard/events') }} @endif" class="btn btn-theme my-3">@if($order_type == "menuitem") My Orders @else My Events @endif</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

