<div class="setting-main-area tab-pane fade @if(last(request()->segments()) == 'profile') active show @endif "  id="dashboard">
    <div class="text-right">
        <div class="d-lg-none profile-asw-menu d-inline"><i class="fa fa-bars"></i></div>
    </div>
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

                <div class="col-md-7 profile-form">

                    <h4 class="font-montserrat">Edit profile </h4>                               
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">	
                    <input type="hidden" name="location_id" value="@if(isset($profile)){{$profile->location_id}}@endif"> 

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label for="name" class="font-montserrat">Full Name</label>
                                <input type="text" class="form-control font-montserrat" name="name" placeholder="First Name" id="name" value="@if(isset($profile)){{$profile->name}}@endif" maxlength="95">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label for="email" class="font-montserrat">Email</label>
                                <input type="email" class="form-control font-montserrat" name="email" placeholder="Email" id="email" value="@if(isset($profile)){{$profile->email}}@endif">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="font-montserrat">Mobile Number</label>
                                <input class="form-control font-montserrat" type="text" pattern="[0-9]{10}" name="mobile" placeholder="Mobile No" 
                                value="@if(isset($profile)){{$profile->mobile}}@endif" id="mobile">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class=" text-center">
                                <button class="btn btn-default font-montserrat" type="submit" id="upd_profile" style="width: 100%">Update profile</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>