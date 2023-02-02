@extends('main.app') @section('content')
<section class="">
    <div class="userss user_settings mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="float-left ">
                        <h5 class=" font-weight-bold f_30">Settings</h5> </div>
                </div>
            </div>
            <div class="row mt-3 mb-4">
                <div class="col-md-12">
                    <ul class="nav nav-tabs creditbtn mb-4 mt-3" id="myTab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active  f_18" id="" data-toggle="tab" href="#ongoing" role="tab" aria-controls="home" aria-expanded="true">Account Settings</a> </li>
                        <li class="nav-item"> <a class="nav-link f_18" id="" data-toggle="tab" href="#deleted" role="tab" aria-controls="profile" aria-expanded="false">Change Password</a> </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-div m-1">
                        <div class="tab-content" id="myTabContent">
                            <div role="tabpanel" class="tab-pane fade active show" id="ongoing" aria-labelledby="home-tab" aria-expanded="true">
                                <div class="row  px-4 py-4">
                                    <div class="col-md-12">
                                        <p class="f_16">kindly fill out all the fields</p>
                                    </div>
                                    <div class="col-md-3">
                                            <p class="font-weight-bold pb-2 mb-0 f_16">Photo</p>
                                        <div class="image gallery-group-1">
                                            <div class="card">
                                                <div class="hovereffect">
                                                    <img id="card-img-top" class="card-img-top"
                                                         src="{{ getProfileImage() }}"
                                                         alt="Card image cap">
                                                    <div class="overlay">
                                                        <div class="rotate">
                                                            <p class="group1">
                                                                <a id="imagelink" href="{{ getProfileImage() }}"
                                                                   data-lightbox="gallery-group-1">
                                                                    <i class="fa fa-image"></i>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <!-- <div class="profile_set">
                                            <div class="prof_pic">
                                                <div class="prof_img">
                                                    <img src="{!! asset('assets/front/img/register.png') !!}"> <span class="fa fa-image"></span> </div>
                                            </div>
                                        </div> -->
                                        <div class="row my-3">
                                            <div class="col">
                                                <div class="float-left">
                                                    <p id="file-chosen" class=" mb-0 text-muted f_12">accept.png.jpg</p>
                                                    <p class="mb-0 f_12 text-muted ">maximum of 5mb</p>
                                                </div>
                                            </div>
                                            <div class="col text-right">
                                                <input hidden accept="image/*" type="file" id="actual-btn" class="form-control-file" value="Browse">
                                                <label for="actual-btn" class="font-weight-bold bg_darkblue view_more_btn  text-light">Browse</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <form id="user_dashboard_form">
                                            @if($UserData->id ?? '')
                                                <input type="hidden" id="id" name="id" value="{{ $UserData->id ?? '' }}">
                                            @endif
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="first_name">First Name</label>
                                                        <input type="text" name="first_name" id="first_name" value="{{$UserData->first_name}}" class="form-control"> </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="last_name">Last Name</label>
                                                        <input type="text" name="last_name" id="last_name" value="{{$UserData->last_name}}" class="form-control"> </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Mail</label>
                                                        <input type="email" name="email" id="email" value="{{$UserData->email}}" class="form-control"> </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="phone_number">Contact number</label>
                                                        <input type="text" name="phone_number" id="phone_number" value="{{$UserData->phone_number}}" class="form-control"> </div>
                                                </div>
                                                <div class="col-md-12 text-right mt-5">
                                                    <button type="button" class="delete_account btn btn_b_radius bg-dark text-light px-5"> Delete account</button>
                                                    <button type="submit" class="btn btn_b_radius  bg_blue px-5"> Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade  " id="deleted" aria-labelledby="home-tab" aria-expanded="true">
                                <div class="px-4 py-4">
                                    <form id="password-user">
                                        <div class="form-group">
                                            <label for="old">Current Password</label>
                                            <input type="password" name="old" id="old" value="" class="form-control"> </div>
                                        <div class="form-group">
                                            <label for="password">Confirm Password</label>
                                            <input type="password" name="password" id="password" value="" class="form-control"> </div>
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm New Password</label>
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"> </div>
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn_b_radius bluebtn_h bg_blue px-5"> Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> @endsection