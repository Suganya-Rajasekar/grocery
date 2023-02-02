@extends('main.app')
@section('content')
<style>
.tab-content {
    height: auto;
    /*overflow-y: scroll;*/
    scrollbar-width: none;
}
#wishlist ul {
    margin: 0px;
    padding: 0px;
    overflow: hidden;
}
a:not([href]) {
    color: #f65a60;
    text-decoration: none;
}
#wishlist li {
    list-style: none;
    overflow: hidden;
}
h4 {
    color: #212121 !important;
    font-size: 25px !important;
    margin-bottom: 20px !important;
    font-weight: 600 !important;
}

.back-btn{
	    background: #f65a60;
    border-radius: 50%;
   
    /* padding-left: 50px; */
    /* margin-left: 11px; */
    float: right;
    margin: auto;
    bottom: -19px;
    
}

</style>
<section>
        <div class="error-message-area">
          <div class="error-content">
            <h4 class="error-msg"></h4>
          </div>
        </div>

   <div class="main-area" style="padding-top: 8px;">
      <div class="container-fluid">
     
         <div class="row fixedtop mt-30">
            <div class="col-lg-3">
               <div class="settings-main-menu">
                  <nav>
                     <ul class="nav nav-tabs">
                        <li class="nsec mb-30" data-val="dashboard">
                           <div class="icons">
                              <img src="{{url('/assets/img/edit.svg')}}">
                           </div>
                           <!-- <i class="fas fa-tachometer-alt mr-3"></i> -->
                           <a href="#dashboard" data-toggle="tab" class="active">
                           Edit Profile
                           </a>
                        </li>
                        <li class="nsec mb-30" data-val="password">
                           <div class="icons">
                              <img src="{{url('/assets/img/password.svg')}}">
                           </div>
                           <!-- <i class="fas fa-lock mr-3"></i> -->
                           <a href="#password" data-toggle="tab">
                           Password
                           </a>
                        </li>
                        <li class="nsec mb-30" data-val="orders">
                           <div class="icons">
                              <img src="{{url('/assets/img/myorderrs.svg')}}">
                           </div>
                           <!-- <i class="fas fa-clone mr-3"></i> -->
                           <a href="#orders" data-toggle="tab">
                           My Orders 
                           </a>
                        </li>
                        <li class="nsec mb-30" data-val="orders2">
                           <div class="icons">
                              <img src="{{url('/assets/img/myorderrs.svg')}}">
                           </div>
                           <!-- <i class="fas fa-clone mr-3"></i> -->
                           <a href="#orders2" data-toggle="tab">
                           My Orders2 
                           </a>
                        </li>
                        <li class="nsec mb-30" data-val="bookmark">
                           <div class="icons">
                              <img src="{{url('/assets/img/bookmark.svg')}}">
                           </div>
                           <!-- <i class="fas fa-heart mr-3"></i> -->
                           <a href="#bookmark" data-toggle="tab">
                           Bookmark 
                           </a>
                        </li>
                        <li class="nsec mb-30" data-val="wishlist">
                           <div class="icons">
                              <img src="{{url('/assets/img/wishlist.svg')}}">
                           </div>
                           <!-- <i class="fas fa-heart mr-3"></i> -->
                           <a href="#wishlist" data-toggle="tab">
                           Wishlist 
                           </a>
                        </li>
                        <li class="nsec mb-30" data-val="favorite">
                           <div class="icons">
                              <img src="{{url('/assets/img/favorite.svg')}}">
                           </div>
                           <!-- <i class="fas fa-heart mr-3"></i> -->
                           <a href="#favorite" data-toggle="tab">
                           Favorite
                           </a>
                        </li>
                        <li class="nsec mb-30">
                           <div class="icons">
                              <img src="{{url('/assets/img/logout.svg')}}">
                           </div>
                           <a href="http://localhost/emperica/logout" onclick="event.preventDefault();document.getElementById('logout-form-tab').submit();" data-toggle="tab">
                           Logout 
                           </a>
                           <form id="logout-form-tab" action="http://localhost/emperica/logout" method="GET" class="d-none">
                              <input type="hidden" name="_token" value="0HzGBWThqR7l2Ar52HHVhnslecsNyXLQnaBlnj3Q">  
                           </form>
                        </li>
                     </ul>
                  </nav>
               </div>
            </div>                                          
            <div class="col-lg-9 right-cont">
               <div class="tab-content">
                  <div class="setting-main-area tab-pane fade   active show " id="dashboard">
                     <!-- {{ url('customerProfile/update') }} -->
                     <form action="" method="POST" id="user_settings_form" enctype="multipart/form-data">
                        <div class="settings-content-area shadow-cont">
                           <div class="row">
                              <div class="col-md-3">
                                 <div class="right-img p-3" style="border:none;">
                                    <div class="right-img-border">
                                       <div class="image m-auto">
                                          <button class="browse-btn back-btn" type="button"><span class="cam fa fa-pencil"></span>
                                          <input class="form-control media-img" type="file" name="avatar" id="avatar" accept="image/png, image/jpeg" style="height: 12px;">
                                          </button>
                                          @php 
                                             use Illuminate\Support\Str;  
                                             $myString = $profile->avatar;     
                                             $contains = Str::contains($myString, ['.png','.jpeg','jpg']); 
                                          @endphp
                                          <img src="@if($contains == 1){{$profile->avatar}}@else{{url('/storage/app/public/avatar.png')}}@endif" alt="">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-7">

                                 <h4>Edit profile</h4>                               
                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">	
                                 <input type="hidden" name="location_id" value="@if(isset($profile)){{$profile->location_id}}@endif"> 

                                 <div class="row">
                                    <div class="col-lg-12">
                                       <div class="form-group mb-4">
                                          <label for="name">Full Name</label>
                                          <input type="text" class="form-control" name="name" placeholder="First Name" id="name" value="@if(isset($profile)){{$profile->name}}@endif" maxlength="95" >
                                       </div>
                                    </div>
                                    <div class="col-lg-12">
                                       <div class="form-group mb-4">
                                          <label for="email">Email</label>
                                          <input type="email" class="form-control" name="email" placeholder="Email" id="email" value="@if(isset($profile)){{$profile->email}}@endif" >
                                       </div>
                                    </div>
                                    <div class="col-lg-12">
                                       <div class="form-group mb-4">
                                          <label>Mobile Number</label>
                                          <input class="form-control" type="text" pattern="[0-9]*" name="mobile" placeholder="Mobile No" 
                                          value="@if(isset($profile)){{$profile->mobile}}@endif" >
                                       </div>
                                    </div>
                                    <div class="col-lg-12">
                                       <div class=" text-center">
                                          <button class="btn btn-default" type="submit" id="upd_profile" style="width: 100%">Update profile</button>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                     </form>

                  </div>
                  <div class="setting-main-area tab-pane fade  verification_area " id="password">
                     <div class="settings-content-area shadow-cont">
                        <h4 class="mb-4">Change Password</h4>
                        <form action="http://localhost/emperica/author/settings/update" method="POST" id="user_pass_form">
                           <input type="hidden" name="_token" value="0HzGBWThqR7l2Ar52HHVhnslecsNyXLQnaBlnj3Q">	
                           <div class="row">
                              <div class="col-lg-8">
                                 <div class="col-lg-12">
                                    <div class="form-group mb-4">
                                       <label for="current_password">Current Password</label>
                                       <input type="password" class="form-control" placeholder="Current Password" name="current_password" id="current_password" required="">
                                       <div class="fa-dash" id="eye1" onclick="pswvis()">
                                          <i class="fas fa-eye-slash text-muted" id="login-psw"></i>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group mb-4">
                                       <label for="new_password">New Password</label>
                                       <input type="password" class="form-control" placeholder="New Password" name="password" id="new_password" required="">
                                       <div class="fa-dash" id="eye1" onclick="pswvis()">
                                          <i class="fas fa-eye-slash text-muted" id="login-psw"></i>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group mb-4">
                                       <label for="confirm_password">Confirm Password</label>
                                       <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" id="confirm_password" required="">
                                       <div class="fa-dash" id="eye1" onclick="pswvis()">
                                          <i class="fas fa-eye-slash text-muted" id="login-psw"></i>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class=" ">
                                       <button type="submit" class="btn btn-default" style="width: 100%">Update Password</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
                  <div class="setting-main-area tab-pane fade  verification_area " id="orders">
                     <div class="settings-content-area">
                        <h4>My Orders</h4>
                        <div class="shadow-table">
                           <div class="row">
                              <div class="col-md-4">
                                 <div class=" text-center">
                                    <button class="btn btn-default orders-back" type="submit">In process</button>
                                 </div>
                              </div>
                              <div class="col-md-4"> </div>
                              <div class="col-md-4">
                                 <div class=" text-center">
                                    <button class="btn btn-default" type="submit" style="width: 100%">Past orders</button>
                                 </div>
                              </div>
                           </div>
                           <br>
                           <div class="accordion" id="accordionExample">
                              <div class="card">
                                 <div class="card-header" id="headingOne">
                                    <button class="button-order" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="width: 100%;background:none">
                                       <div class="row">
                                          <div class="col-md-3">
                                             <p class="fnt">Order id #4</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p class="fnt">18 Dec</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p class="fnt">2 items</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p><b class="fnt">$45.50</b></p>
                                          </div>
                                       </div>
                                    </button>
                                 </div>
                                 <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body" style="padding: 30px">
                                       <ul>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <b>
                                             <li class="mb-50">
                                                <div class="li-left">SubTotal	</div>
                                                <div class="li-right">$66</div>
                                             </li>
                                          </b>
                                          <br>
                                          <li>
                                             <div class="review-btn float-right">
                                                <a href="#" class="btn btn-theme" data-toggle="modal" data-target="#reviews">Reviews</a>
                                             </div>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                              <!-- The Modal -->
                              <div class="modal" id="reviews">
                                 <div class="modal-dialog">
                                    <div class="modal-content">
                                       <div class="modal-body">
                                          <button type="button" class="close" data-dismiss="modal">×</button>
                                          <div class="chef-det mt-5">
                                             <div class="d-flex">
                                                <div class=" ">
                                                   <div class="chefimg">
                                                      <img src="https://www.mediterraneanyachtshow.gr/images/stories/com_form2content/p32/f1516/medys-chef-competition.jpg" alt="">
                                                   </div>
                                                </div>
                                                <div class="w-100 ml-3">
                                                   <div class="o-hid">
                                                      <div class="float-left">
                                                         <div class="chefname">
                                                            <h3 class="text-black font-weight-bold">Lily</h3>
                                                            <h5 class="text-muted">South indian</h5>
                                                            <h5 class="text-muted">ID #13</h5>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="o-hid">
                                                <div class="ft">
                                                   <div class="sqr-star my-3">
                                                      <h5 class="font-weight-bold text-black">Rate your chef</h5>
                                                      <div class=" star-rating ">
                                                         <div class="overflow-hidden">
                                                            <div class="float-left">
                                                               <input name="shop_rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating">
                                                               <label for="condition_5" class="star-rating-star js-star-rating">
                                                                  <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                                     <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                                  </svg>
                                                               </label>
                                                               <input name="shop_rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating">
                                                               <label for="condition_4" class="star-rating-star js-star-rating">
                                                                  <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                                     <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                                  </svg>
                                                               </label>
                                                               <input name="shop_rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating">
                                                               <label for="condition_3" class="star-rating-star js-star-rating">
                                                                  <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                                     <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                                  </svg>
                                                               </label>
                                                               <input name="shop_rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating">
                                                               <label for="condition_2" class="star-rating-star js-star-rating">
                                                                  <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                                     <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                                  </svg>
                                                               </label>
                                                               <input name="shop_rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating">
                                                               <label for="condition_1" class="star-rating-star js-star-rating">
                                                                  <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                                     <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                                  </svg>
                                                               </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="feedback-form">
                                                      <h5 class="font-weight-bold text-black">Share your feedback</h5>
                                                      <form action="">
                                                         <textarea name="" id="" rows="5" width="100%" class="form-control"></textarea>
                                                         <input type="button" class="btn btn-theme float-right mt-4 mb-2" value="submit">
                                                      </form>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="card">
                                 <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0"></h2>
                                    <button class=" button-order collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="width: 100%">
                                       <div class="row">
                                          <div class="col-md-3">
                                             <p class="fnt">Order id #4</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p class="fnt">18 Dec</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p class="fnt">2 items</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p><b class="fnt">$45.50</b></p>
                                          </div>
                                       </div>
                                    </button>
                                 </div>
                                 <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body" style="padding: 30px">
                                       <ul>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <b>
                                             <li class="mb-50">
                                                <div class="li-left">SubTotal	</div>
                                                <div class="li-right">$66</div>
                                             </li>
                                          </b>
                                          <br>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                              <div class="card">
                                 <div class="card-header" id="headingThree">
                                    <h2 class="mb-0">  </h2>
                                    <button class="button-order collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="width: 100%">
                                       <div class="row">
                                          <div class="col-md-3">
                                             <p class="fnt">Order id #4</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p class="fnt">18 Dec</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p class="fnt">2 items</p>
                                          </div>
                                          <div class="col-md-3">
                                             <p><b class="fnt">$45.50</b></p>
                                          </div>
                                       </div>
                                    </button>
                                 </div>
                                 <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body" style="padding: 30px">
                                       <ul>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <li class="mb-30">
                                             <div class="li-left">Vietnamese chilli chicken	</div>
                                             <div class="li-right">$22</div>
                                          </li>
                                          <br>
                                          <b>
                                             <li class="mb-50">
                                                <div class="li-left">SubTotal	</div>
                                                <div class="li-right">$66</div>
                                             </li>
                                          </b>
                                          <br>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="setting-main-area tab-pane fade  verification_area " id="orders2">
                     <!-- <div class="settings-content-area">
                        <div class="shadow-table">
                        <h4>Rattings &amp; Reviews</h4>
                        <div class="row">
                        	<div class="col-lg-12">
                        <div class="table-responsive new_table">
                        	<table class="table">
                        <thead class="thead-dark">
                        	<tr>
                        <th scope="col">#</th>
                        <th scope="col">Order ID</th>
                        <th scope="col">Resturant Name</th>
                        <th scope="col">Resturant Ratting</th>
                        <th scope="col">Resturant Review</th>
                        <th scope="col">Boy Name</th>
                        <th scope="col">Boy Review</th>
                        <th scope="col">Boy Ratting</th>
                        	</tr>
                        </thead>
                        <tbody>
                        	</tbody>
                        	</table>
                        </div>
                        	</div>
                        	</div>
                        </div>
                        	</div> -->
                     <div class="settings-content-area">
                        <h4>My Orders</h4>
                        <div class="shadow-table">
                           <div class="col-md-12">
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class=" text-center">
                                       <!-- <button class="btn btn-default" 
                                          type="submit">In process</button> -->
                                       <button class="btn btn-default" type="submit" style="width: 100%">In process</button>
                                    </div>
                                 </div>
                                 <div class="col-md-4"> </div>
                                 <div class="col-md-4">
                                    <div class=" text-center">
                                       <button class="btn btn-default orders-back" type="submit" style="width: 100%">Past orders</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <br>
                           <div class="accordion" id="accordionExample">
                              <div class="card">
                                 <div class="card-header" id="headingOne">
                                    <div class="row">
                                       <div class="col-md-3">
                                          <p class="fnt">Order id #4</p>
                                       </div>
                                       <div class="col-md-3">
                                          <p class="fnt">18 Dec</p>
                                       </div>
                                       <div class="col-md-3">
                                          <p class="fnt">2 items</p>
                                       </div>
                                       <div class="col-md-3">
                                          <p><b class="fnt">$45.50</b></p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card-body" style="padding: 30px">
                                    <div class="row d-flex justify-content-center">
                                       <div class="col-12">
                                          <ul id="progressbar" class="text-center">
                                             <li class="active step0">
                                                <span class="order-status"> Ordered </span>
                                                <div class="circle-img">
                                                   <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/tick.svg" style="width: 100%">
                                                </div>
                                             </li>
                                             <li class="active step0">
                                                <span class="order-status"> Preparing </span>
                                                <div class="circle-img1">
                                                   <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/pan.svg" style="width: 100%">
                                                </div>
                                             </li>
                                             <li class=" step0">
                                                <span class="order-status"> On the way </span>
                                                <div class="circle-img2">
                                                   <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/shipping.svg" style="width: 100%">
                                                </div>
                                             </li>
                                             <li class=" step0">
                                                <span class="order-status"> Delivered </span>
                                                <div class="circle-img3">
                                                   <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/courier.svg" style="width: 100%">
                                                </div>
                                             </li>
                                          </ul>
                                       </div>
                                    </div>
                                    <ul>
                                       <li class="mb-30">
                                          <div class="li-left">Vietnamese chilli chicken	</div>
                                          <div class="li-right">$22</div>
                                       </li>
                                       <br>
                                       <li class="mb-30">
                                          <div class="li-left">Vietnamese chilli chicken	</div>
                                          <div class="li-right">$22</div>
                                       </li>
                                       <br>
                                       <li class="mb-30">
                                          <div class="li-left">Vietnamese chilli chicken	</div>
                                          <div class="li-right">$22</div>
                                       </li>
                                       <br>
                                       <b>
                                          <li class="mb-50">
                                             <div class="li-left">SubTotal	</div>
                                             <div class="li-right">$66</div>
                                          </li>
                                       </b>
                                       <br>
                                    </ul>
                                 </div>
                              </div>
                              <div class="card">
                                 <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0"></h2>
                                    <div class="row">
                                       <div class="col-md-3">
                                          <p class="fnt">Order id #4</p>
                                       </div>
                                       <div class="col-md-3">
                                          <p class="fnt">18 Dec</p>
                                       </div>
                                       <div class="col-md-3">
                                          <p class="fnt">2 items</p>
                                       </div>
                                       <div class="col-md-3">
                                          <p><b class="fnt">$45.50</b></p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card-body" style="padding: 30px">
                                    <div class="row d-flex justify-content-center">
                                       <div class="col-12">
                                          <ul id="progressbar" class="text-center">
                                             <li class="active step0">
                                                <span class="order-status"> Ordered </span>
                                                <div class="circle-img">
                                                   <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/tick.svg" style="width: 100%">
                                                </div>
                                             </li>
                                             <li class="active step0">
                                                <span class="order-status"> Preparing </span>
                                                <div class="circle-img1">
                                                   <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/pan.svg" style="width: 100%">
                                                </div>
                                             </li>
                                             <li class=" step0">
                                                <span class="order-status"> On the way </span>
                                                <div class="circle-img2">
                                                   <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/shipping.svg" style="width: 100%">
                                                </div>
                                             </li>
                                             <li class=" step0">
                                                <span class="order-status"> Delivered </span>
                                                <div class="circle-img3">
                                                   <img src="http://localhost/emperica/script/main-content/Themes/main/public/img/courier.svg" style="width: 100%">
                                                </div>
                                             </li>
                                          </ul>
                                       </div>
                                    </div>
                                    <ul>
                                       <li class="mb-30">
                                          <div class="li-left">Vietnamese chilli chicken	</div>
                                          <div class="li-right">$22</div>
                                       </li>
                                       <br>
                                       <li class="mb-30">
                                          <div class="li-left">Vietnamese chilli chicken	</div>
                                          <div class="li-right">$22</div>
                                       </li>
                                       <br>
                                       <li class="mb-30">
                                          <div class="li-left">Vietnamese chilli chicken	</div>
                                          <div class="li-right">$22</div>
                                       </li>
                                       <br>
                                       <b>
                                          <li class="mb-50">
                                             <div class="li-left">SubTotal	</div>
                                             <div class="li-right">$66</div>
                                          </li>
                                       </b>
                                       <br>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="setting-main-area tab-pane fade  verification_area " id="bookmark">
                     <!-- <div class="settings-content-area">
                        <div class="shadow-table">
                        	<h4>Past Offers</h4>
                        	<div class="row">
                        <div class="col-lg-12">
                        	<div class="table-responsive new_table">
                        <table class="table">
                        	<thead class="thead-dark">
                        <tr>
                        	<th scope="col">Offer Code</th>
                        	<th scope="col">Description</th>
                        	<th scope="col">Status</th>
                        	<th scope="col">No of times used</th>
                        	<th scope="col">Action</th>
                        </tr>
                        	</thead>
                        	<tbody>
                        	<tr>
                        <th>OFF5</th>
                        <td>OFF5</td>
                        <td>
                        	<div class="badge  ">Expired</div>
                        </td>
                        <td>0</td>
                        <td style="cursor: pointer;" class="view_more_cinfo">View Details
                        	<div class="more_info" style="display: none;">
                        <p>OFF5</p>
                        <p><b>5% on all foods</b></p>
                        <p>OFF5</p>
                        	<p>Terms and Conditions</p>
                        	<ul>
                        	<li>Valid on all payment methods.</li>
                        	<li>Maximum available discount is $ 25</li>
                        	<li>Valid 5 time(s) per user</li>
                        	<li>Couon Expired!</li>
                        	</ul>
                        	</div>
                        </td>
                        	</tr>
                        	<tr>
                        <th>OFF10</th>
                        <td>OFF10</td>
                        <td>
                        	<div class="badge  ">Expired</div>
                        </td>
                        <td>0</td>
                        <td style="cursor: pointer;" class="view_more_cinfo">View Details
                        	<div class="more_info" style="display: none;">
                        <p>OFF10</p>
                        <p><b>10% on all foods. Min order $150</b></p>
                        <p>OFF10</p>
                        	<p>Terms and Conditions</p>
                        	<ul>
                        	<li>Minimum cart value is $ 150</li>
                        	<li>Valid on all payment methods.</li>
                        	<li>Maximum available discount is $ 35</li>
                        	<li>Valid 10 time(s) per user</li>
                        	<li>Couon Expired!</li>
                        	</ul>
                        	</div>
                        </td>
                        	</tr>
                        	<tr>
                        <th>OFF25</th>
                        <td>OFF25</td>
                        <td>
                        	<div class="badge  ">Expired</div>
                        </td>
                        <td>0</td>
                        <td style="cursor: pointer;" class="view_more_cinfo">View Details
                        	<div class="more_info" style="display: none;">
                        <p>OFF25</p>
                        <p><b>$25 on all foods. Min order $500</b></p>
                        <p>OFF25</p>
                        	<p>Terms and Conditions</p>
                        	<ul>
                        	<li>Minimum cart value is $ 500</li>
                        	<li>Valid on all payment methods.</li>
                        	<li>Maximum available discount is $ 25</li>
                        	<li>Valid 5 time(s) per user</li>
                        	<li>Couon Expired!</li>
                        	</ul>
                        	</div>
                        </td>
                        	</tr>
                        	</tbody>
                        </table>
                        	</div>
                        </div>
                        	</div>
                        </div>
                        	</div> -->
                     <div class="settings-content-area">
                        <div class="searchbychef-das">
                           <div class="chef-lists">
                              <div class="chefdetails my-3">
                                 <div class="d-flex">
                                    <div class=" ">
                                       <div class="chefimg">
                                          <img src="https://images-na.ssl-images-amazon.com/images/I/31NUb3AhHCL.jpg" alt="">
                                       </div>
                                    </div>
                                    <div class="w-100 ml-3">
                                       <div class="o-hid">
                                          <div class="float-left">
                                             <div class="chefname">
                                                <h2 class="text-black font-weight-bold">Lily</h2>
                                                <h4 class="text-muted">South indian</h4>
                                             </div>
                                          </div>
                                          <div class="float-right">
                                             <div class="tag-ribbon">
                                                <span class="fa fa-bookmark"></span>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="o-hid">
                                          <div class="float-left">
                                             <div class="sqr-star mt-3">
                                                <div class=" star-rating ">
                                                   <div class="overflow-hidden">
                                                      <div class="float-left">
                                                         <input name="shop_rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating">
                                                         <label for="condition_5" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                         <input name="shop_rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating">
                                                         <label for="condition_4" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                         <input name="shop_rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating">
                                                         <label for="condition_3" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                         <input name="shop_rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating">
                                                         <label for="condition_2" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                         <input name="shop_rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating">
                                                         <label for="condition_1" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                      </div>
                                                      <span class="star-points text-black">4.5</span>
                                                      <div class="float-right">(</div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="float-right">
                                             <div class="pricefornos">
                                                <h4>$20 for  two</h4>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="chefsfood">
                                 <div class="row mt-5">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                       <div class="chefsfoodlists">
                                          <div class="foodimg">
                                             <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
                                          </div>
                                          <div class="fooddesc">
                                             <h3 class="food-name text-black">
                                                vadai
                                             </h3>
                                             <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                          </div>
                                          <div class="foodprice">
                                             <h3 class="text-black">$10</h3>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                       <div class="chefsfoodlists">
                                          <div class="foodimg">
                                             <img src="https://images2.minutemediacdn.com/image/upload/c_crop,h_1126,w_2000,x_0,y_181/f_auto,q_auto,w_1100/v1554932288/shape/mentalfloss/12531-istock-637790866.jpg" alt="">
                                          </div>
                                          <div class="fooddesc">
                                             <h3 class="food-name text-black">
                                                vadai
                                             </h3>
                                             <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                          </div>
                                          <div class="foodprice">
                                             <h3 class="text-black">$10</h3>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                       <div class="chefsfoodlists">
                                          <div class="foodimg">
                                             <img src="https://www.uvdesk.com/wp-content/uploads/2019/07/Food-Delivery-2.jpeg" alt="">
                                          </div>
                                          <div class="fooddesc">
                                             <h3 class="food-name text-black">
                                                vadai
                                             </h3>
                                             <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                          </div>
                                          <div class="foodprice">
                                             <h3 class="text-black">$10</h3>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                       <div class="chefsfoodlists">
                                          <div class="foodimg">
                                             <img src="https://scx1.b-cdn.net/csz/news/800a/2016/howcuttingdo.jpg" alt="">
                                          </div>
                                          <div class="fooddesc">
                                             <h3 class="food-name text-black">
                                                vadai
                                             </h3>
                                             <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                          </div>
                                          <div class="foodprice">
                                             <h3 class="text-black">$10</h3>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <br>
                        <div class="searchbychef-das">
                           <div class="chef-lists">
                              <div class="chefdetails my-3">
                                 <div class="d-flex">
                                    <div class=" ">
                                       <div class="chefimg">
                                          <img src="https://images-na.ssl-images-amazon.com/images/I/31NUb3AhHCL.jpg" alt="">
                                       </div>
                                    </div>
                                    <div class="w-100 ml-3">
                                       <div class="o-hid">
                                          <div class="float-left">
                                             <div class="chefname">
                                                <h2 class="text-black font-weight-bold">Lily</h2>
                                                <h4 class="text-muted">South indian</h4>
                                             </div>
                                          </div>
                                          <div class="float-right">
                                             <div class="tag-ribbon">
                                                <span class="fa fa-bookmark"></span>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="o-hid">
                                          <div class="float-left">
                                             <div class="sqr-star mt-3">
                                                <div class=" star-rating ">
                                                   <div class="overflow-hidden">
                                                      <div class="float-left">
                                                         <input name="shop_rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating">
                                                         <label for="condition_5" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                         <input name="shop_rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating">
                                                         <label for="condition_4" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                         <input name="shop_rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating">
                                                         <label for="condition_3" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                         <input name="shop_rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating">
                                                         <label for="condition_2" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                         <input name="shop_rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating">
                                                         <label for="condition_1" class="star-rating-star js-star-rating">
                                                            <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                               <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                            </svg>
                                                         </label>
                                                      </div>
                                                      <span class="star-points text-black">4.5</span>
                                                      <div class="float-right">(</div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="float-right">
                                             <div class="pricefornos">
                                                <h4>$20 for  two</h4>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="chefsfood">
                                 <div class="row mt-5">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                       <div class="chefsfoodlists">
                                          <div class="foodimg">
                                             <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
                                          </div>
                                          <div class="fooddesc">
                                             <h3 class="food-name text-black">
                                                vadai
                                             </h3>
                                             <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                          </div>
                                          <div class="foodprice">
                                             <h3 class="text-black">$10</h3>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                       <div class="chefsfoodlists">
                                          <div class="foodimg">
                                             <img src="https://images2.minutemediacdn.com/image/upload/c_crop,h_1126,w_2000,x_0,y_181/f_auto,q_auto,w_1100/v1554932288/shape/mentalfloss/12531-istock-637790866.jpg" alt="">
                                          </div>
                                          <div class="fooddesc">
                                             <h3 class="food-name text-black">
                                                vadai
                                             </h3>
                                             <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                          </div>
                                          <div class="foodprice">
                                             <h3 class="text-black">$10</h3>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                       <div class="chefsfoodlists">
                                          <div class="foodimg">
                                             <img src="https://www.uvdesk.com/wp-content/uploads/2019/07/Food-Delivery-2.jpeg" alt="">
                                          </div>
                                          <div class="fooddesc">
                                             <h3 class="food-name text-black">
                                                vadai
                                             </h3>
                                             <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                          </div>
                                          <div class="foodprice">
                                             <h3 class="text-black">$10</h3>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                       <div class="chefsfoodlists">
                                          <div class="foodimg">
                                             <img src="https://scx1.b-cdn.net/csz/news/800a/2016/howcuttingdo.jpg" alt="">
                                          </div>
                                          <div class="fooddesc">
                                             <h3 class="food-name text-black">
                                                vadai
                                             </h3>
                                             <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                          </div>
                                          <div class="foodprice">
                                             <h3 class="text-black">$10</h3>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="setting-main-area tab-pane fade  verification_area " id="favorite">
                     <div class="searchbychef">
                        <div class="chef-lists">
                           <div class="chefdetails my-3">
                              <div class="d-flex">
                                 <div class=" ">
                                    <div class="chefimg">
                                       <img src="https://images-na.ssl-images-amazon.com/images/I/31NUb3AhHCL.jpg" alt="">
                                    </div>
                                 </div>
                                 <div class="w-100 ml-3">
                                    <div class="o-hid">
                                       <div class="float-left">
                                          <div class="chefname">
                                             <h2 class="text-black font-weight-bold">Lily</h2>
                                             <h4 class="text-muted">South indian</h4>
                                          </div>
                                       </div>
                                       <div class="float-right">
                                          <div class="tag-ribbon">
                                             <span class="fa fa-bookmark"></span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="o-hid">
                                       <div class="float-left">
                                          <div class="sqr-star mt-3">
                                             <div class=" star-rating ">
                                                <div class="overflow-hidden">
                                                   <div class="float-left">
                                                      <input name="shop_rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating">
                                                      <label for="condition_5" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating">
                                                      <label for="condition_4" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating">
                                                      <label for="condition_3" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating">
                                                      <label for="condition_2" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating">
                                                      <label for="condition_1" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                   </div>
                                                   <span class="star-points text-black">4.5</span>
                                                   <div class="float-right">(</div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="float-right">
                                          <div class="pricefornos">
                                             <h4>$20 for  two</h4>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="chefsfood">
                              <div class="row mt-5">
                                 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                    <div class="chefsfoodlists">
                                       <div class="foodimg">
                                          <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
                                       </div>
                                       <div class="fooddesc">
                                          <h3 class="food-name text-black">
                                             vadai<i class="fa fa-heart pink-heart"></i>
                                          </h3>
                                          <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                       </div>
                                       <div class="foodprice">
                                          <h3 class="text-black">$10</h3>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                    <div class="chefsfoodlists">
                                       <div class="foodimg">
                                          <img src="https://images2.minutemediacdn.com/image/upload/c_crop,h_1126,w_2000,x_0,y_181/f_auto,q_auto,w_1100/v1554932288/shape/mentalfloss/12531-istock-637790866.jpg" alt="">
                                       </div>
                                       <div class="fooddesc">
                                          <h3 class="food-name text-black">
                                             vadai<i class="fa fa-heart pink-heart"></i>
                                          </h3>
                                          <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                       </div>
                                       <div class="foodprice">
                                          <h3 class="text-black">$10</h3>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                    <div class="chefsfoodlists">
                                       <div class="foodimg">
                                          <img src="https://www.uvdesk.com/wp-content/uploads/2019/07/Food-Delivery-2.jpeg" alt="">
                                       </div>
                                       <div class="fooddesc">
                                          <h3 class="food-name text-black">
                                             vadai<i class="fa fa-heart pink-heart"></i>
                                          </h3>
                                          <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                       </div>
                                       <div class="foodprice">
                                          <h3 class="text-black">$10</h3>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                    <div class="chefsfoodlists">
                                       <div class="foodimg">
                                          <img src="https://scx1.b-cdn.net/csz/news/800a/2016/howcuttingdo.jpg" alt="">
                                       </div>
                                       <div class="fooddesc">
                                          <h3 class="food-name text-black">
                                             vadai<i class="fa fa-heart pink-heart"></i>
                                          </h3>
                                          <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                       </div>
                                       <div class="foodprice">
                                          <h3 class="text-black">$10</h3>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <br>
                     <div class="searchbychef">
                        <div class="chef-lists">
                           <div class="chefdetails my-3">
                              <div class="d-flex">
                                 <div class=" ">
                                    <div class="chefimg">
                                       <img src="https://images-na.ssl-images-amazon.com/images/I/31NUb3AhHCL.jpg" alt="">
                                    </div>
                                 </div>
                                 <div class="w-100 ml-3">
                                    <div class="o-hid">
                                       <div class="float-left">
                                          <div class="chefname">
                                             <h2 class="text-black font-weight-bold">Lily</h2>
                                             <h4 class="text-muted">South indian</h4>
                                          </div>
                                       </div>
                                       <div class="float-right">
                                          <div class="tag-ribbon">
                                             <span class="fa fa-bookmark"></span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="o-hid">
                                       <div class="float-left">
                                          <div class="sqr-star mt-3">
                                             <div class=" star-rating ">
                                                <div class="overflow-hidden">
                                                   <div class="float-left">
                                                      <input name="shop_rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating">
                                                      <label for="condition_5" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating">
                                                      <label for="condition_4" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating">
                                                      <label for="condition_3" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating">
                                                      <label for="condition_2" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="1" id="condition_1" class="star-rating-input inverted-rating">
                                                      <label for="condition_1" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                   </div>
                                                   <span class="star-points text-black">4.5</span>
                                                   <div class="float-right">(</div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="float-right">
                                          <div class="pricefornos">
                                             <h4>$20 for  two</h4>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="chefsfood">
                              <div class="row mt-5">
                                 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                    <div class="chefsfoodlists">
                                       <div class="foodimg">
                                          <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
                                       </div>
                                       <div class="fooddesc">
                                          <h3 class="food-name text-black">
                                             vadai<i class="fa fa-heart pink-heart"></i>
                                          </h3>
                                          <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                       </div>
                                       <div class="foodprice">
                                          <h3 class="text-black">$10</h3>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                    <div class="chefsfoodlists">
                                       <div class="foodimg">
                                          <img src="https://images2.minutemediacdn.com/image/upload/c_crop,h_1126,w_2000,x_0,y_181/f_auto,q_auto,w_1100/v1554932288/shape/mentalfloss/12531-istock-637790866.jpg" alt="">
                                       </div>
                                       <div class="fooddesc">
                                          <h3 class="food-name text-black">
                                             vadai<i class="fa fa-heart pink-heart"></i>
                                          </h3>
                                          <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                       </div>
                                       <div class="foodprice">
                                          <h3 class="text-black">$10</h3>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                    <div class="chefsfoodlists">
                                       <div class="foodimg">
                                          <img src="https://www.uvdesk.com/wp-content/uploads/2019/07/Food-Delivery-2.jpeg" alt="">
                                       </div>
                                       <div class="fooddesc">
                                          <h3 class="food-name text-black">
                                             vadai<i class="fa fa-heart pink-heart"></i>
                                          </h3>
                                          <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                       </div>
                                       <div class="foodprice">
                                          <h3 class="text-black">$10</h3>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <br>
                     <div class="searchbychef">
                        <div class="chef-lists">
                           <div class="chefdetails my-3">
                              <div class="d-flex">
                                 <div class=" ">
                                    <div class="chefimg">
                                       <img src="https://images-na.ssl-images-amazon.com/images/I/31NUb3AhHCL.jpg" alt="">
                                    </div>
                                 </div>
                                 <div class="w-100 ml-3">
                                    <div class="o-hid">
                                       <div class="float-left">
                                          <div class="chefname">
                                             <h2 class="text-black font-weight-bold">Lily</h2>
                                             <h4 class="text-muted">South indian</h4>
                                          </div>
                                       </div>
                                       <div class="float-right">
                                          <div class="tag-ribbon">
                                             <span class="fa fa-bookmark"></span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="o-hid">
                                       <div class="float-left">
                                          <div class="sqr-star mt-3">
                                             <div class=" star-rating ">
                                                <div class="overflow-hidden">
                                                   <div class="float-left">
                                                      <input name="shop_rating" type="radio" value="5" id="condition_5" class="star-rating-input inverted-rating">
                                                      <label for="condition_5" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="4" id="condition_4" class="star-rating-input inverted-rating">
                                                      <label for="condition_4" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="3" id="condition_3" class="star-rating-input inverted-rating">
                                                      <label for="condition_3" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <input name="shop_rating" type="radio" value="2" id="condition_2" class="star-rating-input inverted-rating">
                                                      <label for="condition_2" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                      <label for="condition_1" class="star-rating-star js-star-rating">
                                                         <svg class="svg-inline--fa fa-star fa-w-18" aria-hidden="true" data-prefix="fa" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                                            <path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
                                                         </svg>
                                                      </label>
                                                   </div>
                                                   <span class="star-points text-black">4.5</span>
                                                   <div class="float-right">(</div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="float-right">
                                          <div class="pricefornos">
                                             <h4>$20 for  two</h4>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="chefsfood">
                              <div class="row mt-5">
                                 <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-5">
                                    <div class="chefsfoodlists">
                                       <div class="foodimg">
                                          <img src="https://www.cdc.gov/foodsafety/images/comms/food-Safety-Tips-small.jpg" alt="">
                                       </div>
                                       <div class="fooddesc">
                                          <h3 class="food-name text-black">
                                             vadai<i class="fa fa-heart pink-heart"></i>
                                          </h3>
                                          <p class="elipsis-text text-muted">Lorem ipsum dolor sit amet consectetur, adipisicing, elit.</p>
                                       </div>
                                       <div class="foodprice">
                                          <h3 class="text-black">$10</h3>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="container setting-main-area tab-pane  fade  verification_area " id="wishlist">
                     <div class="col-md-12" style="padding: 14px;">
                        <h4>Wish list</h4>
                        <div class="">
                           <button class="btn btn-default wish-btn" type="submit">Save</button>
                        </div>
                     </div>
                     <div class="settings-content-area set-pad ">
                        <div class="row">
                           <div class="col-lg-7">
                              <div class="form-group mb-4">
                                 <label for="name">Food Name</label>
                                 <input type="text" class="form-control" name="first_name" placeholder="First Name" id="first_name" value="Customer" maxlength="95">
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group mb-4">
                                 <label for="name">Discription</label>
                                 <input type="text" class="form-control" name="first_name" placeholder="First Name" id="first_name" value="Customer" maxlength="95">
                              </div>
                           </div>
                        </div>
                     </div>
                     <br>
                     <ul>
                        <li>
                           <div style="float: left;">
                              <h4>Chettinad briyani</h4>
                              <p>Lorem Ipsum is Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>
                              <span>Lorem Ipsum is Lorem Ipsum</span>
                           </div>
                           <div style="float: right;">
                              <p>05/01/20121</p>
                              <a style="color: #f65a60">Remove</a>
                           </div>
                        </li>
                        <br>
                        <li>
                           <div style="float: left;">
                              <h4>Chettinad briyani</h4>
                              <p>Lorem Ipsum is Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>
                              <span>Lorem Ipsum is Lorem Ipsum</span>
                           </div>
                           <div style="float: right;">
                              <p>05/01/20121</p>
                              <a style="color: #f65a60">Remove</a>
                           </div>
                        </li>
                        <br>
                        <li>
                           <div style="float: left;">
                              <h4>Chettinad briyani</h4>
                              <p>Lorem Ipsum is Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>
                              <span>Lorem Ipsum is Lorem Ipsum</span>
                           </div>
                           <div style="float: right;">
                              <p>05/01/20121</p>
                              <a style="color: #f65a60">Remove</a>
                           </div>
                        </li>
                        <br>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection

