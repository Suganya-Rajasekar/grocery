
<div class="setting-main-area tab-pane  fade  verification_area @if(last(request()->segments()) == 'wishlist') active show @endif  " id="wishlist">
    <input type="hidden" id="Page" value='1'>
    <form id="wishlistForm" method="post">
        @if(!(\Request::has('pageNumber')))
        <div class="col-md-12" style="padding: 14px;">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="d-inline mb-0 font-opensans">Wishlist</h4>
                <div class="d-flex justify-content-end align-items-center">
                    <button class="btn btn-default wish-btn" type="submit">Save</button>
                    <div class="d-lg-none float-right profile-asw-menu d-inline"><i class="fa fa-bars"></i></div>
                </div>
            </div>
        </div>
        <div class="settings-content-area set-pad ">
            <div class="row">
                <div class="col-lg-7">
                    <div class="form-group mb-4">
                        <label for="name" class="font-montserrat">Food Name</label>
                        <input type="text" class="form-control font-montserrat" name="title" placeholder="Title" id="title" maxlength="95">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group mb-4">
                        <label for="name" class="font-montserrat">Description</label>
                        <input type="text" class="form-control font-montserrat" name="description" placeholder="Description" id="description"  maxlength="95">
                    </div>
                </div>
            </div>
        </div>
        <br>@endif
        <ul>
            @if(isset($wishlist))
            @foreach($wishlist->data as $k => $v)
            <li>
                <div style="float: left;">
                    <h4 class="font-opensans">{{ strip_tags($v->title) }}</h4>
                    <p class="font-montserrat">{{ strip_tags($v->description) }}</p>
                    <span class="font-montserrat"> </span>
                </div>
                <div style="float: right;">
                    <p class="font-montserrat">{{ \Carbon\Carbon::parse($v->created_at)->format('d/m/Y')}}</p>
                    <a style="color: #f65a60" class="font-montserrat" href="javascript:void(0);" onclick="removeWhish({{ $v->id }} )"  >Remove</a>
                </div>
            </li>
            <br>
            @endforeach
            @endif

        </ul>
        <div class="paginate"></div>
        @if(!(\Request::has('pageNumber')) && $wishlist->data != '' )
        <button class="btn btn-default col-md-12 profileModule"  type="button" name="wishlist">Load More</button>
        @else 
        @if(!(\Request::has('pageNumber')))
        <div class="text-center">
            <img src="https://sustain.round.glass/wp-content/themes/sustain/assets/images/no-results.png" alt="" width="200px">
            <p class="font-montserrat">No wishlist marked yet.</p>
        </div>
        @endif
        @endif

    </form>
</div>