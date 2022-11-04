
<body class="sidebar-noneoverflow">
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
            <ul class="navbar-nav theme-brand flex-row  text-center">
                <li class="nav-item theme-text theme-logo">
                    <a href="{{ url('dashboard') }}" class="nav-link">DIGITRANS</a>
                </li>
                <li class="nav-item ml-2 toggle-sidebar">
                    <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3" y2="6"></line><line x1="3" y1="12" x2="3" y2="12"></line><line x1="3" y1="18" x2="3" y2="18"></line></svg></a>
                </li>
            </ul>
            <ul class="navbar-item flex-row navbar-dropdown">
                <li class="nav-item dropdown apps-dropdown more-dropdown md-hidden">
                    <a class="text-white font-weight-bold" href="javascript:void(0)">{{ date('l, d F Y') }}</a>
                </li>
            </ul>
            <ul class="navbar-item flex-row navbar-dropdown search-ul">
                <li class="nav-item language-dropdown">
                    <div class="custom-dropdown-icon">
                        <a class="dropdown-toggle btn" href="{{ url('file_manager') }}">File Manager</a>
                    </div>
                </li>
                <li class="nav-item dropdown language-dropdown">
                    <div class="dropdown custom-dropdown-icon">
                        <a class="dropdown-toggle btn" href="#" role="button" id="langDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>Pengaturan</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="langDropdown">
                            <a class="dropdown-item" href="{{ url('activity_log') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg> Aktivitas
                            </a>
                            <a class="dropdown-item" href="{{ url('profile') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Profile
                            </a>
                            <a class="dropdown-item" href="{{ url('lock_screen') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Layar Kunci
                            </a>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="logout()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Keluar
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>

