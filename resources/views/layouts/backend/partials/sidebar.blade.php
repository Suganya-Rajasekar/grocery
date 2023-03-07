<div class="sidebar sidebar-main">
	<div class="sidebar-content">
		<div class="sidebar-user">
			<div class="category-content">
				<div class="media">
					<a href="{!! url(getRoleName().'/profile/'.\Auth::user()->id.'/edit') !!}" class="media-left">
						<img src="{!! \Auth::user()->avatar !!}" class="rounded-circle mr-1" alt="" style="width: 40px;">
					</a>
					<div class="media-body">
						<span class="media-heading text-semibold">{!! \Auth::user()->name !!}</span>
						<div class="text-size-mini text-muted">
							@if(\Auth::user()->role == 1)
							<i class="icon-envelope  text-size-small"></i> &nbsp;{!! \Auth::user()->email !!}
							@else
							<i class="icon-user text-size-small"></i> &nbsp;{!! \Auth::user()->profile_name !!}
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Main navigation -->
		<div class="sidebar-category sidebar-category-visible scrollbar" id="style-7">
			<div class="category-content no-padding">
				<div class="close-admin-menu d-md-none">
					<span class="text-black">X</span>
				</div>
				<ul class="navigation navigation-main navigation-accordion">
					<?php $a_role=\Auth::user()->role; $a_role_name=(\Auth::user()->role==1 || \Auth::user()->role==5) ? 'admin' : 'vendor'; 
					?>
					<!-- Main -->
					{{-- <li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li> --}}
					@foreach(getSideMenuMain() as $key => $adminmainmenus)                
					<?php
					$role   = json_decode($adminmainmenus->role,true);
					$viewpermisson  = ($a_role == 5) ? getUserModuleAccess($adminmainmenus->route,'view') : $role[$a_role]['view'];
					?>
					@if(count(getSideMenuSub($adminmainmenus->id))>0)
					@if($role!='' && $viewpermisson =='1')
					<li class="dropdown {{ Request::is($a_role_name.'/'.$adminmainmenus->url) ? 'active' : '' }}">
						<a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="{{ $adminmainmenus->icon ?? 'fa fa-columns' }}"></i> <span>{{ $adminmainmenus->menu_name }}</span></a>
						<ul >
							@foreach(getSideMenuSub($adminmainmenus->id) as $ch_key => $adminmenuChild)
							<?php $srole=json_decode($adminmenuChild->role,true);
								// if($adminmenuChild->route == 'chef'){
								//     echo $adminmenuChild->route;
								//     exit;
								// }
							$viewpermisson1 = $a_role == 5 ? getUserModuleAccess($adminmenuChild->route,'view') : $srole[$a_role]['view'];
							?>
							@if($role!='' && $viewpermisson1 =='1')
							<li @if(url($a_role_name.'/'.$adminmenuChild->route) == url()->current()) class="active" @endif><a class="nav-link" href="{{ url($a_role_name.'/'.$adminmenuChild->route) }}"><i class="{{ $adminmenuChild->icon ?? 'fa fa-columns' }}"></i> <span>{{ $adminmenuChild->menu_name }}</span></a></li>
							@endif
							@endforeach
						</ul>
					</li>
					@endif
					@else
					@if($role!='' && $viewpermisson =='1')
					<li class=" {{ Request::is($a_role_name.'/'.$adminmainmenus->url) ? 'active' : '' }}">
						<a class="nav-link "  href="{{ url($a_role_name.'/'.$adminmainmenus->route) }}">
							<i class="{{ $adminmainmenus->icon ?? 'fa fa-columns' }}"></i> <span>{{ $adminmainmenus->menu_name }}
							</span>
						</a>
					</li>
					@endif
					@endif
					@endforeach 
				</ul>
			</div>
		</div>
		<!-- /main navigation -->
	</div>
</div>

<script type="text/javascript">
	/*$('.sidebar-category').animate({
		scrollTop: $("li.active").offset().top
	}, 100);*/
</script>