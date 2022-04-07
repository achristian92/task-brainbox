<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Search form -->
            <ul class="navbar-nav mr-auto">
                @if ($userCurrent->isAdminOrSupervisor() && request()->segment(1) !== 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                            <i class="fa fa-arrow-right mr-1 ml-1" aria-hidden="true"></i>
                            Panel
                        </a>
                    </li>
                @endif
                @if ($userCurrent->isCollaborator() && request()->segment(1) !== 'collaborator')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('counter.planned.index','view=calendar') }}">
                            <i class="fa fa-arrow-right mr-1 ml-1" aria-hidden="true"></i>
                            Mi Plan
                        </a>
                    </li>
                @endif
            </ul>
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center  ml-md-auto ">
                <li class="nav-item d-xl-none">
                    <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
                <li class="nav-item d-sm-none">
                    <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                        <i class="ni ni-zoom-split-in"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                @if($userCurrent->hasRole('admin'))
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ni ni-bell-55"></i> {{ $notifications->count() }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
                            <!-- Dropdown header -->
                            <div class="px-3 py-3">
                                <h6 class="text-sm text-muted m-0">Tienes <strong class="text-primary">{{ $notifications->count() }}</strong> actividad por aprobar</h6>
                            </div>
                            <!-- List group -->
                            <div class="list-group list-group-flush">
                                @foreach($notifications as $alert)
                                    <a href="#!" class="list-group-item list-group-item-action">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <!-- Avatar -->
                                                <img alt="Image placeholder" src="{{ $alert->user->urlImg() }}" class="avatar rounded-circle">
                                            </div>
                                            <div class="col ml--2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="mb-0 text-sm">{{ $alert->user->full_name }}</h4>
                                                    </div>
                                                    <div class="text-right text-muted">
                                                        <small>{{ $alert->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                                <p class="text-sm mb-0">{{ Str::limit($alert->name,45) }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach


                            </div>
                            <!-- View all -->
                            <a href="{{ route('admin.imboxv2.index','typeTab=evaluation') }}" class="dropdown-item text-center text-primary font-weight-bold py-3">Ver todos</a>
                        </div>
                    </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="{{ $userCurrent->urlImg() }}">
                  </span>
                            <div class="media-body  ml-2  d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold">{{$userCurrent->name}}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right ">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Bienvenido!</h6>
                        </div>
                        <a href="{{ route('account.index') }}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>Mi cuenta</span>
                        </a>
                        @if($userCurrent->hasRole('admin'))
                            <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                                <i class="ni ni-settings"></i>
                                <span>Ajustes</span>
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ $userCurrent->getRouteToLogout() }}" class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="ni ni-user-run"></i>
                            <span>Salir</span>
                        </a>
                        <form action="{{ $userCurrent->getRouteToLogout() }}" id="logout-form" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
