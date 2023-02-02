@extends('main.app')
@section('content')
<section class="checkouts " style="margin-bottom: 100px;margin-top: 30px;">
    <div class="container-fluid">
        <style>

            .checkout-details-list ul li {
                padding: 30px 25px;
                box-shadow: 0 0 9px 2px #8080803b;
                margin: 18px 10px 46px 42px;
                position: relative;
            }
            .check-head .check-icon {
                float: left;
                position: absolute;
                left: -42px;
                background: #f45d6f;
                padding: 16px;
                box-shadow: 0 0 9px 2px #8080803b;
                color: white;
            }
            .checkout-details-list ul li:last-child .left-border{
                border-left: 0;
            }
            .left-border {
                border-left: 1px dashed #f45d6f;
                height: 100%;
                display: block;
                position: absolute;
                left: -26px;
                top: 70px;
            }
            .payment-tab #v-pills-tab {
                background: #f45d6f33;
                height: 180px;
            }
            .payment-tab .nav-pills .nav-link.active,payment-tab .nav-pills .show > .nav-link {
                color: #e10202;
                background-color: #fff;
            }
            .payment-tab {
                display: grid;
                grid-template-columns: 33% 63%;
            }
            .payment-tab #v-pills-tab a {
                margin: 18px 0 3px 15px;

            }
            .paument-img img {
                width: 100%;
            }
            .paument-img {
                width: 172px;
                margin: 0 auto;
            }
            .online-payment{
                text-align: center;
            }
            .cod {
                border: 1px solid;
                padding: 14px;
            }
            .cod span:first-child {
                margin-right: 20px;
            }
            .chef-checkout-img {
                width: 80px;
            }
            .chef-checkout-img img {
                width: 100%;
                border-radius: 50%;
                height: 80px;
            }
            .chefs-checkout {
                box-shadow:0 0 9px 2px #8080803b;
                overflow: hidden;
                margin-top: 20px;
            }
            .chef-check-det {
                overflow: hidden;
            }
            .chef-check-det .svg-inline--fa.fa-w-18 {
                width: 19px;
                padding: 4px;
            }
            .chef-check-det .star-rating-star {
                padding: 1px;
            }
            .chef-check-det h6 {
                font-size: 14px;
            }
            .chefs-checkout-top {
                background: var(--light-theme);
                padding: 20px 10px 20px 20px;
            }
            .price-list li,.extra , .grand-ttl,.loyalty-pnt,.grand-total ul li,.over-hid{
                overflow: hidden;
            }
            .price-list li,.foodandprice span {
                font-size: 24px;
            }
            .check-food-details,.grand-total ul{
                padding: 20px 10px 20px 20px;
            }
            .customer-image img {
                width: 100%;
                border-radius: 50%;
            }
            .customer-image  {
                width: 100px;
            }


            @media(max-width:1200px){
                .payment-tab {
                    display: unset;
                }
                .payment-tab #v-pills-tab a {
                    margin: 18px 0 0px 15px;
                }
                .payment-tab .flex-column {
                    -ms-flex-direction: row !important;
                    flex-direction: row !important;
                }
                .payment-tab #v-pills-tab {
                    height: auto;
                }
            }
        </style>




        <section style="margin-top:30px;">
            <div class="container-fluid">
                <div class="row">

                    <!-- Modal -->



                    <style>
                        /* The container */
                        .radio-container {
                          display: block;
                          position: relative;
                          padding-left: 35px;
                          margin-bottom: 12px;
                          cursor: pointer;
                          font-size: 16px;
                          -webkit-user-select: none;
                          -moz-user-select: none;
                          -ms-user-select: none;
                          user-select: none;
                      }

                      /* Hide the browser's default radio button */
                      .radio-container input {
                          position: absolute;
                          opacity: 0;
                          cursor: pointer;
                      }

                      .checkmark {
                        position: absolute;
                        top: 6px;
                        left: 0;
                        height: 20px;
                        width: 20px;
                        background-color: #fff;
                        border-radius: 50%;
                        border: 1px solid #e2e2e2;
                    }

                    /* On mouse-over, add a grey background color */
                    .radio-container:hover input ~ .checkmark {
                      background-color: #ccc;
                  }

                  /* When the radio button is checked, add a blue background */
                  .radio-container input:checked ~ .checkmark {
                      background-color: #2196F3;
                  }

                  /* Create the indicator (the dot/circle - hidden when not checked) */
                  .checkmark:after {
                      content: "";
                      position: absolute;
                      display: none;
                  }

                  /* Show the indicator (dot/circle) when checked */
                  .radio-container input:checked ~ .checkmark:after {
                      display: block;
                  }

                  /* Style the indicator (dot/circle) */
                  .radio-container .checkmark:after {
                    top: 5px;
                    left: 5px;
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: white;
                }
                .modalimg img {
                    width: 100%;
                    height: 100%;
                }
                .modal-xl {
                    max-width: 1673px;
                }
                .comment-stat li {
                    display: inline-block;
                    padding-right: 16px;
                }
            </style>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1">
              exampleModal1
          </button> 

          <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-md">
                <div class="modal-content">

                  <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="modalimg">
                                <img src="https://www.seriouseats.com/recipes/images/2014/09/20140918-jamie-olivers-comfort-food-insanity-burger-david-loftus.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="close">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modalfooddet">
                                <div class="foodstat">
                                    <ul class="prep-time">
                                        <li class="font-weight-bold">
                                            <span class="float-left">Veg Burger</span>
                                            <span class="float-right">$30</span>
                                        </li>
                                        <li>
                                            <span class="float-left">Preperation time</span>
                                            <span class="float-right">2hr</span>
                                        </li>
                                    </ul>
                                    <div class="details">
                                        <h4>Details</h4>
                                        <p class="text-muted muted-font">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, nulla.</p>
                                    </div>
                                </div>
                                <div class="commend-food">
                                    <div class="custcomment">
                                        <div class="customer-image float-left mr-3">
                                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQ_u-gnmeOdsfZMOpBTzqqcnBZwo8dWu38-Q&amp;usqp=CAU" alt="">
                                        </div>
                                        <div class="customer-comment over-hid">
                                            <div class="custname-likes  ">
                                                <div class="d-flex">
                                                    <div class="float-left">
                                                        <h4 class="font-weight-bold">lilly</h4>
                                                        <p class="text-secondary"> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat, eos? Voluptatem recusandae nihil repellendus possimus amet. Eveniet autem, cum ipsam.</p>
                                                        <ul class="comment-stat text-muted">
                                                            <li>
                                                                <span class="text-muted">12hr</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-muted">12 Likes</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-muted">reply</span>
                                                            </li>
                                                        </ul>
                                                        <div class="viewreplies text-muted my-2">
                                                            --------<a href="#" class="text-muted font-weight-bold">View reply(2)</a>
                                                        </div>
                                                    </div>
                                                    <div class="float-right">
                                                        <i class="fa fa-heart"></i>
                                                    </div>
                                                </div>
                                                <div class="cust-reply d-flex">
                                                    <div class="customer-image float-left mr-3">
                                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQ_u-gnmeOdsfZMOpBTzqqcnBZwo8dWu38-Q&amp;usqp=CAU" alt="">
                                                    </div>
                                                    <div class="float-left">
                                                        <h4 class="font-weight-bold">lilly</h4>
                                                        <p class="text-secondary"> Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat, eos? Voluptatem recusandae nihil repellendus possimus amet. Eveniet autem, cum ipsam.</p>
                                                        <ul class="comment-stat text-muted">
                                                            <li>
                                                                <span class="text-muted">12hr</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-muted">12 Likes</span>
                                                            </li>
                                                            <li>
                                                                <span class="text-muted">reply</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="chefdetails overflow-hidden mt-3">
                                <div class="float-left">
                                    <ul>
                                        <li><a href="#"><span class="fa fa-heart"></span></a></li>
                                        <li><a href="#"><span class="fa fa-comment"></span></a></li>
                                        <li><a href="#"><span class="fa fa-share-alt"></span></a></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
      Launch demo modal
  </button> 

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header pinkbox">
            <h5 class="modal-title" id="exampleModalLabel">
                <span class="foodname">Customized biriyani</span>
                <div>ghlj</div>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
      </div>
      <div class="modal-body">
        <div class="addon-sec over-hid">
            <div class="modalbody-head">
                <h5 class="text-black font-weight-bold">Addons</h5>
            </div>
            <div class="cont" style="line-height: 2;">
                <ul class="over-hid">
                    <li>
                        <div class="float-left">
                            <div class=" custom-checkbox">
                               <label class="container-check" for="">Biriyani
                                   <input type="checkbox" class="custom-control-input">
                                   <span class="checkmarks"></span>
                               </label>
                           </div>
                       </div>
                       <div class="float-right">
                        <div class="foodprice">20$</div>
                    </div>
                </li>
                <li>
                    <div class="float-left">
                        <label class="radio-container">One
                            <input type="radio" checked="checked" name="radio">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="float-right">
                        <div class="foodprice">20$</div>
                    </div>
                </li>
                <li>
                    <div class="float-left">
                        <label class="radio-container">One
                            <input type="radio" checked="checked" name="radio">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="float-right">
                        <div class="foodprice">20$</div>
                    </div>
                </li>
                <li>
                    <div class="float-left">
                        <div class=" custom-checkbox">
                           <label class="container-check" for="">Biriyani
                               <input type="checkbox" class="custom-control-input">
                               <span class="checkmarks"></span>
                           </label>
                       </div>
                   </div>
                   <div class="float-right">
                    <div class="foodprice">20$</div>
                </div>
            </li>
            <li>
                <div class="float-left">
                    <div class=" custom-checkbox">
                       <label class="container-check" for="">Biriyani
                           <input type="checkbox" class="custom-control-input">
                           <span class="checkmarks"></span>
                       </label>
                   </div>
               </div>
               <div class="float-right">
                <div class="foodprice">20$</div>
            </div>
        </li>
    </ul>
</div>
</div>
<div class="addon-sec over-hid">
    <div class="modalbody-head">
        <h5 class="text-black font-weight-bold">Evening</h5>
    </div>
    <div class="cont" style="line-height: 2;">
        <ul class="over-hid">
            <li>
                <div class="float-left">
                    <div class=" custom-checkbox">
                       <label class="container-check" for="">Biriyani
                           <input type="checkbox" class="custom-control-input">
                           <span class="checkmarks"></span>
                       </label>
                   </div>
               </div>
               <div class="float-right">
                <div class="foodprice">20$</div>
            </div>
        </li>
        <li>
            <div class="float-left">
                <label class="radio-container">One
                    <input type="radio" checked="checked" name="radio">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="float-right">
                <div class="foodprice">20$</div>
            </div>
        </li>
        <li>
            <div class="float-left">
                <label class="radio-container">One
                    <input type="radio" checked="checked" name="radio">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="float-right">
                <div class="foodprice">20$</div>
            </div>
        </li>
        <li>
            <div class="float-left">
                <div class=" custom-checkbox">
                   <label class="container-check" for="">Biriyani
                       <input type="checkbox" class="custom-control-input">
                       <span class="checkmarks"></span>
                   </label>
               </div>
           </div>
           <div class="float-right">
            <div class="foodprice">20$</div>
        </div>
    </li>
    <li>
        <div class="float-left">
            <div class=" custom-checkbox">
               <label class="container-check" for="">Biriyani
                   <input type="checkbox" class="custom-control-input">
                   <span class="checkmarks"></span>
               </label>
           </div>
       </div>
       <div class="float-right">
        <div class="foodprice">20$</div>
    </div>
</li>
</ul>
<div class="over-hid">
    <hr>
    <span class="float-left text-theme font-weight-bold f-24">Total</span>
    <span class="float-right text-theme f-24">$20</span>
</div>
</div>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary">Order for today</button>
    <button type="button" class="btn btn-primary">Order for future</button>
</div>
</div>
</div>
</div>
</div>
</div>
</section>

<div class="row">

    <div class="col-md-7 col-lg-7">

        <div class="checkout-details-list">
            <ul>
                <li class="account">
                    <span class="left-border"></span>
                    <div class="check-head">
                        <span class="fa fa-user bg-theme check-icon"></span>
                        <div>
                            <h3>Account</h3>
                            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
                        </div>
                    </div>
                    <div class="acc-forms">
                        <div class="col-lg-6 col-md-8 col-sm-12">
                            <form action="" class="height-60">
                                <p>Create an account or <a href="#"><span class="text-theme">Login to your account</span></a> </p>

                                <div class="form-group">
                                    <input type="text" placeholder="Enter your name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Enter your email id" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Enter your password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Enter your phone number" class="form-control">
                                </div>
                                <p class="text-muted">By creating an account, i accept the terms and conditions</p>
                                <div class="submit-btn">
                                    <a href="#" class="btn btn-theme my-3">Register</a>
                                </div>



                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="9876543210">
                                </div>

                                <div class="form-group">
                                    <input type="text" placeholder="Enter your OTP" class="form-control">
                                </div>
                                
                                <div class="submit-btn">
                                    <a href="#" class="btn btn-theme my-3">Verify OTP</a>
                                </div>



                                <div class="form-group">
                                    <input type="text" placeholder="Enter your phone number" class="form-control">
                                </div>
                                
                                <div class="submit-btn">
                                    <a href="#" class="btn btn-theme my-3">Login</a>
                                </div>


                            </form>
                        </div>
                    </div>
                    <div class="acc-btn">

                        <a href="#" class="btn btn-theme font-weight-normal">Have an account <br> LOGIN</a>
                        <a href="#" class="btn btn-theme font-weight-normal">New user <br> REGISTER NOW</a>
                    </div>
                    <h3 class="text-muted">Welcome Lorem!</h3>
                </li>
                <li class="delivery-add">
                    <span class="left-border"></span>
                    <div class="check-head">
                        <span class="fa fa-user bg-light text-theme check-icon"></span>
                        <div>
                            <h3>Select Your Delivery Address</h3>
                        </div>
                    </div>
                    <div class="acc-forms">
                        <div class="row my-4">
                            <div class="col-lg-6 col-md-12 col-sm-12 my-3">
                                <div class="shadow-box">
                                    <h3 class="text-muted my-3">Work</h3>
                                    <h4 class="text-muted">Lorem ipsum dolor sit amet consectetur.</h4>
                                    <div class="acc-btn my-3">
                                        <a href="#" class="btn btn-theme font-weight-normal">Deliver here</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 my-3">
                                <div class="shadow-box">
                                    <h3 class="text-muted my-3">Add a new address</h3>
                                    <h4 class="text-muted">Lorem ipsum dolor sit amet consectetur.</h4>
                                    <div class="acc-btn my-3">
                                        <a href="#" class="btn btn-theme font-weight-normal px-2"> Add a new address</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-muted">Deliver Address</h3>
                </li>
                <li class="payment">
                    <span class="left-border"></span>
                    <div class="check-head">
                        <span class="fa fa-map-marker-alt  bg-light text-theme check-icon"></span>
                    </div>
                    <h3 class="text-muted">Payment Option</h3>
                    <div class="payment-tab">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                          <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Online payment</a>
                          <!-- <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                          Cash on delivery</a> -->
                      </div>

                      <div class="tab-content mt-4" id="v-pills-tabContent">
                          <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="online-payment">
                                <div class="paument-img my-4">
                                    <img src="{!! asset('assets/img/login-img.svg') !!}">
                                </div>
                               <!--  <div class="pay-btn">
                                    <a href="#" class="btn btn-theme">Online Payment</a>
                                </div> -->
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="online-payment">
                                <div class="col-md-8 m-auto">
                                    <div class="cod">
                                        <span>Razor pay</span><span class="btn btn-theme px-4 py-2">pay 200</span>
                                    </div>
                                </div>

                                <div class="pay-btn my-4">
                                    <a href="#" class="btn btn-theme">Place order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<div class="col-md-5 col-lg-5">
    <div class="chefs-checkout">
        <ul>
            <li>
                <div class="chefs-checkout-list">
                    <div class="chefs-checkout-top">
                        <div class="chef-checkout-img float-left">
                            <img src="https://www.mediterraneanyachtshow.gr/images/stories/com_form2content/p32/f1516/medys-chef-competition.jpg" alt="">
                        </div>
                        <div class="chef-check-det">
                            <div class="">
                                <div style="overflow:hidden">
                                    <div class="float-left">
                                        <h3>Lilly</h3>
                                    </div>
                                    <div class="float-right">
                                        <span class="text-muted">Order date</span><br>
                                        <span class="order-date">
                                            13/7/2021
                                        </span>
                                    </div>
                                </div>
                                <h6 class="text-muted m-0"><span>South indian</span>,<span>North indian</span></h6>
                                <div class="sqr-star">
                                    <div class=" star-rating ">

                                        <div class="overflow-hidden">
                                            <div class="float-left">
                                                <input name="shop_rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating">
                                                <label for="condition_5" class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                </label>
                                                <input name="shop_rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating">
                                                <label for="condition_4" class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                </label>
                                                <input name="shop_rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating">
                                                <label for="condition_3" class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                </label>
                                                <input name="shop_rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating">
                                                <label for="condition_2" class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                </label>
                                                <input name="shop_rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating">
                                                <label for="condition_1" class="star-rating-star js-star-rating">
                                                    <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="check-food-details">
                        <ul class="foodlist">
                            <li>
                                <div class="foodandprice" >
                                    <div style="overflow: hidden;">
                                        <span class="float-left">Biriyani</span>
                                        <span class="float-right text-theme">$10</span>
                                    </div>

                                    <div class="itembuttn">
                                      <span>-</span>
                                      <h5>1</h5>
                                      <span>+</span>
                                  </div>
                              </div>
                              <div class="quantityinput"></div>
                              <div class="extra">
                                <span class="float-left text-muted">Pepsi</span>
                                <span class="float-right text-muted text-theme">$1</span>
                            </div>
                        </li>
                        <li>
                            <div class="foodandprice" style="overflow: hidden;">
                                <span class="float-left">Fried rice</span>
                                <span class="float-right text-theme">$10</span>
                            </div>
                            <div class="quantityinput"></div>

                        </li>
                    </ul>
                    <ul class="price-list">
                        <li><span class="float-left">Service fee</span>
                            <span class="float-right">$10</span>
                        </li>
                        <li><span class="float-left">Delivery charge</span>
                            <span class="float-right">$0.10</span>
                        </li>
                        <li><span class="float-left">Sub Total</span>
                            <span class="float-right">$10</span>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
        <li>
            <div class="chefs-checkout-list">
                <div class="chefs-checkout-top">
                    <div class="chef-checkout-img float-left">
                        <img src="https://www.mediterraneanyachtshow.gr/images/stories/com_form2content/p32/f1516/medys-chef-competition.jpg" alt="">
                    </div>
                    <div class="chef-check-det">
                        <div class="">
                            <div style="overflow:hidden">
                                <div class="float-left">
                                    <h3>Lilly</h3>
                                </div>
                                <div class="float-right">
                                    <span class="text-muted">Order date</span><br>
                                    <span class="order-date">
                                        13/7/2021
                                    </span>
                                </div>
                            </div>
                            <h6 class="text-muted m-0"><span>South indian</span>,<span>North indian</span></h6>
                            <div class="sqr-star">
                                <div class=" star-rating ">

                                    <div class="overflow-hidden">
                                        <div class="float-left">
                                            <input name="shop_rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating">
                                            <label for="condition_5" class="star-rating-star js-star-rating">
                                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                            </label>
                                            <input name="shop_rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating">
                                            <label for="condition_4" class="star-rating-star js-star-rating">
                                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                            </label>
                                            <input name="shop_rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating">
                                            <label for="condition_3" class="star-rating-star js-star-rating">
                                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                            </label>
                                            <input name="shop_rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating">
                                            <label for="condition_2" class="star-rating-star js-star-rating">
                                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                            </label>
                                            <input name="shop_rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating">
                                            <label for="condition_1" class="star-rating-star js-star-rating">
                                                <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="check-food-details">
                    <ul class="foodlist">
                        <li>
                            <div class="foodandprice" style="overflow: hidden;">
                                <span class="float-left">Biriyani</span>
                                <span class="float-right text-theme">$10</span>
                            </div>
                            <div class="quantityinput"></div>
                            <div class="extra">
                                <span class="float-left text-muted">Pepsi</span>
                                <span class="float-right text-muted text-theme">$1</span>
                            </div>
                        </li>
                        <li>
                            <div class="foodandprice" style="overflow: hidden;">
                                <span class="float-left">Fried rice</span>
                                <span class="float-right text-theme">$10</span>
                            </div>
                            <div class="quantityinput"></div>

                        </li>
                    </ul>
                    <ul class="price-list">
                        <li><span class="float-left">Service fee</span>
                            <span class="float-right">$10</span>
                        </li>
                        <li><span class="float-left">Delivery charge</span>
                            <span class="float-right">$0.10</span>
                        </li>
                        <li><span class="float-left">Sub Total</span>
                            <span class="float-right">$10</span>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
    <div class="grand-total bg-light-theme">
        <ul>
            <li>
                <div class="loyalty-pnt">
                    <span class="float-left text-theme">
                        <label class="container-check text-theme" for="">Loyalty points <br> earned
                            <input type="checkbox" class="custom-control-input">
                            <span class="checkmarks"></span>
                        </label>
                    </span>
                    <span class="float-right text-theme">$38</span>
                </div>
            </li>
            <li>
                <span class="float-left text-theme">Knosh wallet</span>
                <span class="float-right text-theme">-$8</span>
            </li>
            <li>
                <div class="grand-ttl py-3 font-weight-bold text-black">
                    <span class="float-left">Grand total</span>
                    <span class="float-right">$38</span>

                </div>
            </li>
        </ul>


    </div>
</div>

</div>
</div>
</div>
</section>
@endsection