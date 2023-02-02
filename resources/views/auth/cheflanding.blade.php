@extends('main.app')

@section('content')

<div class=" mt-100 chefreg-asw ">
    <div class="owl-carousel owl-theme owl-chefreg">
        <div class="item container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="side-cont">
                        <h2 class="py-sm-4 py-2 font-montserrat">Become a bigger star by joining my team of handpicked Chefs and create magic with your authentic dishes</h2>
                        <h3 class="pt-sm-4 pt-2 font-montserrat">Welcome to</h3>
                        <h1 class="font-opensans ">Knosh</h1>
                        <h3 class="pb-sm-4 pb-2 font-montserrat">India's most loved platform that  brings together Popular Chefs, Celebrity Chefs and Master Chefs in one single frame.</h3>
                        <hr>
                        <h3 class="py-sm-4 py-2 font-montserrat">Celebrate life......Celebrate food</h3>
                        <a href="{!!url('chef/register')!!}" class="mt-sm-5 mt-3 font-montserrat">Register Now</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chefimgcar">
                        <div class="">
                            <div class="">
                                <div class="">
                                    <div class="img-asw">
                                        <img src="{{ asset('assets/front/img/img11.jpeg') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="side-cont">
                        <h2 class="py-sm-4 py-2 font-montserrat">Showcase your best dishes to a much larger audience and create a legacy for yourself</h2>
                        <h3 class="pt-sm-4 pt-2 font-montserrat">Welcome to</h3>
                        <h1 class="font-opensans ">Knosh</h1>
                        <h3 class="pb-sm-4 pb-2 font-montserrat">India's most loved platform that  brings together Popular Chefs, Celebrity Chefs and Master Chefs in one single frame.</h3>
                        <hr>
                        <h3 class="py-sm-4 py-2 font-montserrat">Celebrate life......Celebrate food</h3>
                        <a href="{!!url('chef/register')!!}" class="mt-sm-5 mt-3 font-montserrat">Register Now</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chefimgcar">
                        <div class="">
                            <div class="">
                                <div class="">
                                    <div class="img-asw">
                                        <img src="{{ asset('assets/front/img/\img22.jpg') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid my-5 py-3 py-sm-5 chefregbenefits">
        <div>
            <div class="">
                <h1 class="font-opensans">Benefits of Registering with Knosh</h1>
            </div>
            {{-- <div class="p-md-4 p-1">
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="owl-carousel p-4 owl-theme owl-chefreg1">
                                <div class="item">
                                    <div class="Benefits">
                                        <div class="icns">
                                            <img src="https://chefp.in/assets/images/icons/profits.png">
                                        </div>
                                        <div>
                                            <h2 class="py-3 font-opensans">Recognition</h2>
                                            <p class="pb-3 font-montserrat">Knosh provides a unique platform to make sure that thousands of diners hear your story and sample your cooking. The audience on Knosh knows that we are totally focused on promoting home chefs across India.</p>
                                        </div>
                                        <div class="icns1">
                                            <img src="https://chefp.in/assets/images/icons/profits.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="Benefits">
                                        <div class="icns">
                                            <img src="https://chefp.in/assets/images/icons/profits.png">
                                        </div>
                                        <div>
                                            <h2 class="py-3 font-opensans">Profits</h2>
                                            <p class="pb-3 font-montserrat">Knosh provides a unique platform to make sure that thousands of diners hear your story and sample your cooking. The audience on Knosh knows that we are totally focused on promoting home chefs across India.</p>
                                        </div>
                                        <div class="icns1">
                                            <img src="https://chefp.in/assets/images/icons/profits.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="Benefits">
                                        <div class="icns">
                                            <img src="https://chefp.in/assets/images/icons/profits.png">
                                        </div>
                                        <div>
                                            <h2 class="py-3 font-opensans">Convenience</h2>
                                            <p class="pb-3 font-montserrat">Knosh provides a unique platform to make sure that thousands of diners hear your story and sample your cooking. The audience on Knosh knows that we are totally focused on promoting home chefs across India.</p>
                                        </div>
                                        <div class="icns1">
                                            <img src="https://chefp.in/assets/images/icons/profits.png">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="Benefits">
                <div class="">
                    <div class="">
                        <div class="benefit-box d-md-flex justify-content-around">
                            <div class="benefit-box1 top-box">
                                <h2>Knosh focuses on </h2>
                            </div>
                            <div class="curve-1 d-md-block d-none"></div>
                            <div class="benefit-box2">
                                <div class="text">
                                    <div class="text-in d-sm-block d-none">
                                        <span data-weight="1">opportunity</span>
                                        <span data-weight="2"> growth</span>
                                        <span data-weight="3"> PR</span>
                                        <span data-weight="2"> business</span>
                                        <span data-weight="1"> web</span>
                                        <span data-weight="1"> app</span>
                                        <span data-weight="4"> enhanced reach</span>
                                        <span data-weight="1"> safe</span>
                                        <span data-weight="1"> pr</span>
                                        <span data-weight="1"> insights</span>
                                        <span data-weight="1"> market intelligence</span>
                                        <span data-weight="2"> web and app</span>
                                        <span data-weight="1"> popular</span>
                                        <span data-weight="4"> wholesale pricing</span>
                                        <span data-weight="2"> revenue</span>
                                        <span data-weight="1"> promotion</span>
                                        <span data-weight="1"> customer support</span>
                                        <span data-weight="2"> professional</span>
                                        <span data-weight="2"> masterchefs</span>
                                        <span data-weight="1"> success</span>
                                        <span data-weight="2"> expansion</span>
                                        <span data-weight="3" style="font-size: 10px"> training</span>
                                        <span data-weight="4" style="font-size: 20px"> certification</span>
                                        <span data-weight="3"> delivery</span>
                                        <span data-weight="1"> pr</span>
                                        <span data-weight="2"> brand building</span>
                                        <span data-weight="3"> social media</span>
                                        <span data-weight="1"> tech platform</span>
                                        <span data-weight="2"> popular</span>
                                        <span data-weight="4"> videos</span>
                                        <span data-weight="2"> interviews</span>
                                        <span data-weight="1"> photography</span>
                                        <span data-weight="2"> web and app</span>
                                        <span data-weight="2"> taste</span>
                                        <span data-weight="1"> collection</span>
                                        <span data-weight="1"> customer payments</span>
                                        <span data-weight="2"> order taking</span>
                                        <span data-weight="4"> marketing</span>
                                        <span data-weight="2"> insights</span>
                                        <span data-weight="4" style="font-size: 11px"> increased revenue</span>
                                        <span data-weight="1"> popular</span>
                                        <span data-weight="2"> insights</span>
                                        <span data-weight="3"> growth</span>
                                        <span data-weight="2"> pr</span>
                                        <span data-weight="1"> expansion </span>
                                        <span data-weight="3"> market intelligence</span>
                                        <span data-weight="2"> revenue</span>
                                        <span data-weight="1"> masterchefs</span>
                                        <span data-weight="3" style="font-size: 20px"> promotion</span>
                                        <span data-weight="2"> masterchefs</span>
                                        <span data-weight="1"> success</span>
                                        <span data-weight="1"> customer support</span>
                                        <span data-weight="2"> professional</span>
                                        <span data-weight="2"> masterchefs</span>
                                        <span data-weight="1"> growth</span>
                                        <span data-weight="2"> expansion</span>
                                        <span data-weight="4" style="font-size: 10px"> advertising</span>
                                        <span data-weight="2" style="font-size: 10px"> customer support</span>
                                        <span data-weight="4" style="font-size: 16px;"> pr</span>
                                        <span data-weight="3"> web and app</span>
                                        <span data-weight="1"> app</span>
                                        <span data-weight="1"> popular</span>
                                        <span data-weight="1"> revenue</span>
                                        <span data-weight="4" style="font-size: 16px"> complaint management</span>
                                        <span data-weight="3" style="font-size: 20px"> success</span>
                                        <span data-weight="2"> secure</span>    
                                        <span data-weight="1"> web and app</span>
                                        <span data-weight="2"> food</span>
                                        <span data-weight="1" style="font-size: 8px;"> market intelligence</span>
                                        <span data-weight="1"> safety</span>
                                        <span data-weight="1"> time</span>
                                        <span data-weight="1"> promotion</span>
                                        <span data-weight="1"> professional</span>
                                        <span data-weight="1" style="font-size: 8px;">chef</span>
                                    </div>
                                    <div class="text-in d-sm-none">
                                        <span data-weight="1">opportunity</span>
                                        <span data-weight="2"> growth</span>
                                        <span data-weight="3"> PR</span>
                                        <span data-weight="2"> business</span>
                                        <span data-weight="1"> web</span>
                                        <span data-weight="4"> enhanced reach</span>
                                        <span data-weight="2"> training</span>
                                        <span data-weight="1"> market intelligence</span>
                                        <span data-weight="2"> web and app</span>
                                        <span data-weight="1"> food</span>
                                        <span data-weight="4"> wholesale pricing</span>
                                        <span data-weight="1"> revenue</span>
                                        <span data-weight="3" style="font-size: 10px"> training</span><span data-weight="1"> professional</span>
                                        <span data-weight="2"> masterchefs</span>
                                        <span data-weight="1"> success</span>
                                        <span data-weight="1"> platform</span>
                                        <span data-weight="4" style="font-size: 16px"> certification</span>
                                        <span data-weight="1"> expansion</span>
                                        <span data-weight="4"> delivery</span>
                                        <span data-weight="1"> taste</span>
                                        <span data-weight="3"> social media</span>
                                        <span data-weight="1"> tech platform</span>
                                        <span data-weight="4"> videos</span>
                                        <span data-weight="2"> chef</span>
                                        <span data-weight="3"> growth</span>
                                        <span data-weight="1"> popular</span>
                                        <span data-weight="3"> market intelligence</span>
                                        <span data-weight="2"> time</span>
                                        <span data-weight="1"> customer payments</span>
                                        <span data-weight="3"> growth</span>
                                        <span data-weight="1"> success</span>
                                        <span data-weight="2"> order taking</span>
                                        <span data-weight="1"> customer payments</span>
                                        <span data-weight="1"> growth</span>
                                        <span data-weight="2"> pr</span>
                                        <span data-weight="2"> insights</span>
                                        <span data-weight="4"> marketing</span>
                                        <span data-weight="2"> pr</span>
                                        <span data-weight="1"> popular</span>
                                        <span data-weight="4" style="font-size: 11px"> increased revenue</span>
                                        <span data-weight="2"> revenue</span>
                                        <span data-weight="2"> app</span>
                                        <span data-weight="4" style="font-size: 11px"> advertising</span>
                                        <span data-weight="1"> secure</span>
                                        <span data-weight="1"> success</span>
                                        <span data-weight="1"> popularity</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="">
                        <div class="benefit-box d-md-flex justify-content-around">
                            <div class="benefit-box1 top-box">
                                <h2>chef focuses on </h2>
                            </div>
                            <div class="curve-1 d-md-block d-none"></div>
                            <div class="benefit-box2">
                                <img src="{{asset("assets/front/img/cooking-circle.svg")}}" alt="circle-with-text" width="100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-2 py-sm-3 next-step">
        <div class="text-center">
            <h1 class="text-theme font-opensans">What are the next steps</h1>
        </div>
        <div class="">
            <div class="row">
                <div class="col-lg-4 col-md-6 ">
                    <div class="p-3">
                        <div>
                            <img src="{{asset("assets/front/img/Registration2.jpg")}}">
                        </div>
                        <div class="text-center">
                            <h2 class="font-weight-bold py-4 font-opensans">REGISTRATION</h2>
                            <p class="font-montserrat">Register yourself to become a part of elite list of Chefs. Click on the link below and share your details</p>
                            <a href="{!!url('chef/register')!!}" class="mt-sm-5 mt-3 font-montserrat">Register Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 ">
                    <div class="p-3">
                        <div>
                            <img src="{{asset("assets/front/img/BecomeaMember-Onboarding.jpg")}}">
                        </div>
                        <div class="text-center">
                            <h2 class="font-weight-bold py-4 font-opensans">ONBOARDING</h2>
                            <p class="font-montserrat">Our team will share details of our offerings</p>
                            <ul class="text-left font-montserrat pl-5">
                                <li>You need to share your curated menu with your signature dishes</li>
                                <li>Food Trials of your top two speciality dishes</li>
                                <li>Selected handpicked chefs get invited onboard to join Chef Ajay Chopra's team of stars</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 ">
                    <div class="p-3">
                        <div>
                            <img src="{{asset("assets/front/img/StartSelling.jpg")}}">
                        </div>
                        <div class="text-center">
                            <h2 class="font-weight-bold py-4 font-opensans">START SELLING!</h2>
                            <p class="font-montserrat">Finish the paperwork/menu creation and Go Live to create magic with your talent.Journey from a Star to Superstar begins!</p>
                            <p class="text-theme">Celebrate Life........Celebrate Food</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="query">
        <div>
            <h1 class="text-theme">Have a Query?</h1>
            <p>Write to us at info@knosh.in or fill in the form below. One of our team members will get in touch with you soon.</p>
        </div>
        <div class=>
            <div class="row">
                <div class="col-md-7">
                    <form>
                        <div class="row">
                        <div class="col-md-6 py-3">
                            <input type="text" name="name" class="form-control" placeholder="Name*">
                        </div>
                        <div class="col-md-6 py-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">91</span>
                                </div>
                                <input type="text" name="name" class="form-control" placeholder="Mobile*">
                            </div>
                        </div>
                        <div class="col-12 py-3">
                            <input type="email" name="email" class="form-control" placeholder="Email*">
                        </div>
                        <div class="col-12 py-3">
                            <textarea id="message" name="message" rows="4" placeholder="Message*"></textarea>
                        </div>

                        <div class="col-12 py-3">
                            <button>submit</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="col-md-5">
                    <div>
                        <img src="{{asset('assets/front/img/chef-food.png') }}">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection