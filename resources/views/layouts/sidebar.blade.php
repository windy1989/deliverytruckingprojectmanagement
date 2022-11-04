<div class="main-container" id="container">
	<div class="overlay"></div>
	<div class="cs-overlay"></div>
	<div class="search-overlay"></div>
	<div class="sidebar-wrapper sidebar-theme">
		<nav id="sidebar">
			<div class="profile-info">
				<figure class="user-cover-image"></figure>
				<div class="user-info">
					<a href="{{ session('photo') }}" data-lightbox="1" data-title="{{ session('name') }}">
						<img src="{{ session('photo') }}" alt="avatar">
					</a>
					<h6 class="">{{ session('name') }}</h6>
					<p class="">{{ session('email') }}</p>
				</div>
			</div>
			<div class="shadow-bottom"></div>
			<ul class="list-unstyled menu-categories" id="accordionExample">
				<li class="menu {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
					<a href="{{ url('dashboard') }}" aria-expanded="{{ Request::segment(1) == 'dashboard' ? 'true' : 'false' }}" class="dropdown-toggle">
						<div class="">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
							<span>Dashboard</span>
						</div>
					</a>
				</li>
				@php $menu = App\Models\Menu::where('parent_id', 0)->oldest('order')->get(); @endphp
				@foreach($menu as $m)
					@php
						$explode_parent     = explode('/', $m->url);
						$sub_row            = App\Models\Menu::where('parent_id', $m->id)->first();
						$sub                = App\Models\Menu::where('parent_id', $m->id)
							->whereExists(function($query) {
								$query->select(DB::raw(1))
									->from('role_accesses')
									->whereColumn('role_accesses.menu_id', 'menus.id')
									->where('role_accesses.role_id', session('role_id'));
							})
							->oldest('order')
							->get();
					@endphp
					@if($sub->count() > 0)
						@php $explode_sub_parent = explode('/', $sub_row->url); @endphp
						<li class="menu {{ Request::segment(1) == $explode_sub_parent[0] ? 'active' : '' }}">
							<a href="#{{ $explode_sub_parent[0] }}" data-toggle="collapse" aria-expanded="{{ Request::segment(1) == $explode_sub_parent[0] ? 'true' : 'false' }}" class="dropdown-toggle">
								<div class="">
									@if ( $m->name == 'Accounting')
									{!! $m->icon !!}
									<span>{{ $m->name }}</span>	&nbsp;&nbsp;
									<span class="position-absolute top-0 start-100 translate-middle badge badge-warning badge-pill" style="position: absolute;">
									<i class="bi bi-wrench-adjustable" title="Under Maintenance"></i>
									</span>
									
									@else
									{!! $m->icon !!}
									<span>{{ $m->name }}</span>
									@endif
								</div>
								<div>
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
								</div>
							</a>
							<ul class="collapse submenu list-unstyled {{ Request::segment(1) == $explode_sub_parent[0] ? 'recent-submenu show' : '' }}" id="{{ $explode_sub_parent[0] }}" data-parent="#accordionExample">
								@foreach($sub as $s)
									@php $explode_sub = explode('/', $s->url); @endphp
									<li class="{{ Request::segment(1) == $explode_sub_parent[0] && Request::segment(2) == $explode_sub[1] ? 'active' : '' }}">
										<a href="{{ url($s->url) }}">{{ $s->name }}</a>
									</li>
								@endforeach
							</ul>
						</li>
					@else
						@php $check = App\Models\RoleAccess::where('role_id', session('role_id'))->where('menu_id', $m->id)->count(); @endphp
						@if($check > 0)
							<li class="menu {{ Request::segment(1) == $explode_parent[0] ? 'active' : '' }}">
								<a href="{{ url($m->url) }}" aria-expanded="{{ Request::segment(1) == $explode_parent[0] ? 'true' : 'false' }}" class="dropdown-toggle">
									<div class="">
										{!! $m->icon !!}
										<span>{{ $m->name }}</span>
									</div>
								</a>
							</li>
						@endif
					@endif
				@endforeach
			</ul>
		</nav>
	</div>
