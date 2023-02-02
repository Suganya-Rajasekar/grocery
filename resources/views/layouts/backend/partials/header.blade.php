  <!-- Main navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><img style="width: 85px;" src="{!!asset('assets/front/img/logo.svg')!!}">
    </a>
    <div class="d-flex mr-auto">
        {{-- <a class="nav-link" data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a> --}}
        <a class="sidebar-mobile-main-toggle nav-link" title="collapse | expand"><i class="icon-transmission"></i></a>
    </div>
    {{-- <div class="custom-control custom-switch ">
        <input type="checkbox" class="custom-control-input" checked id="customSwitch1">
        <label class="custom-control-label label bg-success" for="customSwitch1">online</label>
    </div> --}}
    @if(getRoleName() == 'vendor')
    <input type="hidden" id="v_id" name="v_id" value="{{Auth::user()->id}}">
    <div class="online-switch-asw mr-2">
        <label class="switch mb-0">
            <input type="checkbox" id="togBtn" name="online_mode" @if(getrestaurantdata()->mode=='open') checked @endif>
            <div class="slider round">
                <span class="on">ONLINE</span>
                <span class="off">OFFLINE</span>
            </div>
        </label>
    </div>
    @else
    <?php
    $notification = App\Models\Notification::with('from_user_info')->where('is_read','=','0');
    $notfy  = clone ($notification); $notcnt = clone ($notification);
    $notfy  = $notfy->limit(5)->get();
    $notcnt = $notcnt->count();
    ?>
        <!-- <nav class="chefdet">
            <ul>
                <li style="line-height: 60px"><a href="#">chef</a>
                <ul class="rgt_set">
                    <h3 class="panel-title"><b>Top performing</b><a class="heading-elements-toggle"></a></h3>
                    <ul style="display:block">
                        <li class="list-style-type mb-1">
                            <div class="tick">
                                <i class="fa fa-check-circle"></i>
                                <span class="ml-3">Dashboard design</span>                          
                            </div>
                        </li>
                        <li class="list-style-type mb-1">
                            <div class="tick">
                                <i class="fa fa-check-circle"></i>
                                <span class="ml-3">Dashboard design</span>                          
                            </div>
                        </li>
                        <li class="list-style-type mb-1">
                            <div class="tick">
                                <i class="fa fa-check-circle"></i>
                                <span class="ml-3">Dashboard design</span>                          
                            </div>
                        </li>
                        <li class="list-style-type mb-1">
                            <div class="tick">
                                <i class="fa fa-check-circle"></i>
                                <span class="ml-3">Dashboard design</span>                          
                            </div>
                        </li>
                        
                    </ul>
                </ul>        
                </li>
            </ul>
        </nav> -->
    <ul class="navbar-nav message-asw w-lg-100">
        <li class="nav-item dropdown ml-auto mr-3">
            <a href="{!! \URL::to(getRoleName().'/notification') !!}" @if($notcnt > 0) data-toggle="dropdown" @endif id="message" class="p-0 nav-link">
                <i class="fa fa-bell"></i>
                <span>{!! $notcnt !!}</span>
            </a>
            @if($notcnt > 0)
            <div class="dropdown-menu" aria-labelledby="message">
                <div class="py-2 d-flex justify-content-between">
                    <span>Message</span>
                    {{-- <span class="edit"><i class="fa fa-edit"></i></span> --}}
                </div>
                @foreach($notfy as $key => $data)
                    <a class="dropdown-item" href="{!! url($data->url) !!}" style="height:100px;width: 250px;">
                         <div class="d-flex">
                            <img src="{!! $data->from_user_info->avatar !!}">
                            <div class="ml-2">
                                <div class="d-flex justify-content-between">
                                    <h2>{!! $data->from_user_info->name !!}</h2>
                                    <span>{!! date('h:i',strtotime($data->created_at)) !!}</span>
                                </div>
                                <div>
                                    <p>{!! $data->title !!}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach 
                <a href="{!! \URL::to(getRoleName().'/notification') !!}" style="height: 50px;width: 265px;" class="btn btn-light btn-block border-0 rounded-top-0" data-popup="tooltip" title="" data-original-title="Load more"><i class="icon-menu7"></i></a>
            </div>
            @endif
        </li>    
    </ul>
    @endif
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#"  id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(empty(Auth::user()->avatar) || !file_exists(Auth::user()->avatar))
                    <img src="{{ Auth::user()->avatar}}" alt="" class="rounded-circle mr-1" style="width: 35px;">
                    @else
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="" class="rounded-circle mr-1"style="width: 35px;">
                    @endif
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @if(getRoleName() == 'admin')
                    <a class="dropdown-item" href="{!! url(getRoleName().'/profile/'.\Auth::user()->id.'/edit') !!}">
                        <i class="fa fa-user mr-2"></i>Profile
                    </a>
                    {{-- <a class="dropdown-item" href="{!! url(getRoleName().'/notification') !!}">
                        <i class="fa fa-bell mr-2"></i>Notifcation
                    </a> --}}
                    <a class="dropdown-item" href="{{ url('') }}">
                        <i class=" icon-display4 mr-2"></i>Main site
                    </a>
                    @else
                    <a class="dropdown-item" href="{!! url(getRoleName().'/chef/'.\Auth::user()->id.'/edit') !!}">
                        <i class="fa fa-user mr-2"></i>Profile
                    </a>
                    <a class="dropdown-item" href="{!! url(getRoleName().'/chef/'.\Auth::user()->id.'/edit_business') !!}">
                        <i class="icon-briefcase mr-2"></i>Business Info
                    </a>
                    <a class="dropdown-item" href="{!! url(getRoleName().'/chef/'.\Auth::user()->id.'/user_documents') !!}">
                        <i class="icon-folder-open mr-2"></i>Documents
                    </a>
                    @endif
                    <a class="dropdown-item profile-dropdown" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <i class="icon-switch2 mr-2"></i>Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
  <!-- /main navbar -->