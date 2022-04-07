<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="sidebar-background" style=""></div>
    <div class="scrollbar-inner" style="position: relative;z-index: 4">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
            <a class="navbar-brand" href="">
{{--                <img src="{{ $setting->url_logo }}" class="navbar-brand-img" alt="{{ $setting->project }}">--}}
                <img src="{{ asset('img/BSW_TASK MANAGER_LOGO.png') }}" class="navbar-brand-img" alt="{{ $setting->project }}">
            </a>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    @if ($userCurrent->isAdminOrSupervisor()  && request()->segment(1) === 'admin' )
                        <li class="nav-item">
                            <a class="nav-link {{isActiveRoute(2,"dashboard")}}" href="{{route('admin.dashboard.index')}}">
                                <img src="{{ asset('img/icons/dashboard.png') }}" class="mr-2" width="14px" alt="customers" >
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{isActiveRoute(2,"customers")}}" href="{{route('admin.customers.index')}}">
                                <img src="{{ asset('img/icons/Clientes.png') }}" class="mr-2" width="18px" alt="customers" >
                                <span class="nav-link-text">Clientes</span>
                            </a>
                        </li>
                        @if ($userCurrent->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{isActiveRoute(2,"users")}}" href="{{route('admin.users.index')}}">
                                    <img src="{{ asset('img/icons/Usuarios.png') }}" class="mr-2" width="16px" alt="customers" >
                                    <span class="nav-link-text">Usuarios</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{isActiveRoute(2,"tags")}}" href="{{route('admin.tags.index')}}">
                                <img src="{{ asset('img/icons/etiquetas.png') }}" class="mr-2" width="16px" alt="customers" >
                                <span class="nav-link-text">Etiquetas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{isActiveRoute(2,"planned")}}" href="{{route('admin.planned.index','view=calendar')}}">
                                <img src="{{ asset('img/icons/Planes de trabajo.png') }}" class="mr-2" width="16px" alt="customers" >
                                <span class="nav-link-text mr-2">Planes de trabajo</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{isActiveRoute(2,"imboxv2")}}" href="{{route('admin.imboxv2.index','typeTab=today')}}" >
                                <img src="{{ asset('img/icons/actividades.png') }}" class="mr-2" width="16px" alt="customers" >
                                <span class="nav-link-text"> Actividades </span>
                                @if ($overdue['general'] > 0)
                                    <span class="badge badge-pill badge-danger" style="font-size: 90%; margin-left: 10px">{{$overdue['general']}}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{isActiveRoute(2,"tracks")}}" href="{{route('admin.tracks.index')}}">
                                <img src="{{ asset('img/icons/seguimiento.png') }}" class="mr-2" width="16px" alt="customers" >
                                <span class="nav-link-text">Seguimiento</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{isActiveRoute(2,"reports")}}" href="{{route('admin.reports.index')}}">
                                <img src="{{ asset('img/icons/Reportes.png') }}" class="mr-2" width="14px" alt="customers" >
                                <span class="nav-link-text">Reportes</span>
                            </a>
                        </li>
                        @if ($userCurrent->isSuperAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{isActiveRoute(2,"documents")}}" href="{{route('admin.documents.index')}}">
                                    <img src="{{ asset('img/icons/Documentos.png') }}" class="mr-2" width="16px" alt="customers" >
                                    <span class="nav-link-text">Documentos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{isActiveRoute(2,"histories")}}" href="{{route('admin.histories.index')}}">
                                    <img src="{{ asset('img/icons/Historial.png') }}" class="mr-2" width="16px" alt="customers" >
                                    <span class="nav-link-text">Historial</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if ($userCurrent->isCollaborator() && request()->segment(1) === 'collaborator')

                    <li class="nav-item">
                        <a class="nav-link {{isActiveRoute(2,"my-planned")}}" href="{{route('counter.planned.index','view=calendar')}}">
                            <img src="{{ asset('img/icons/Planes de trabajo.png') }}" class="mr-2" width="15px" alt="customers" >
                            <span class="nav-link-text">Mi Planeamiento</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isActiveRoute(2,"my-tracksv2")}}" href="{{route('counter.tracksv2.index')}}">
                            <img src="{{ asset('img/icons/seguimiento.png') }}" class="mr-2" width="16px" alt="customers" >
                            <span class="nav-link-text">Mi Productividad</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isActiveRoute(2,"my-imboxv2")}}" href="{{route('counter.imboxv2.index','typeTab=today')}}">
                            <img src="{{ asset('img/icons/actividades.png') }}" class="mr-2" width="15px" alt="customers" >
                            <span class="nav-link-text mr-3">Mis Actividades</span>
                            @if ($overdue['own'] > 0)
                                <span class="badge badge-pill badge-danger" style="font-size: 90%;">{{$overdue['own']}}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{isActiveRoute(2,"my-reports")}}" href="{{route('counter.reports.index')}}">
                            <img src="{{ asset('img/icons/Reportes.png') }}" class="mr-2" width="15px" alt="customers" >
                            <span class="nav-link-text">Mis Reportes</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>

</nav>
