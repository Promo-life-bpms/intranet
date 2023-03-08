<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="toggler">
            <a href="#" class="sidebar-hide d-xl-none d-flex justify-content-end px-5" style="font-size: 2rem"><i
                    class="bi bi-x bi-middle"></i></a>
        </div>

        <div class="sidebar-header">
            <div class="card m-0 p-1">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl">
                        <div class="card-photo" style="width: 40px; height:40px;">
                            @if (auth()->user()->image == null)
                                <a style="color: inherit;" href="{{ route('profile.index') }}">
                                    <p
                                        class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                        <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                    </p>
                                </a>
                            @else
                                <a href="{{ route('profile.index') }}">
                                    <img style="width: 100%; height:100%; object-fit: cover;"
                                        src="{{ asset(auth()->user()->image) }}">
                                </a>
                            @endif

                            {{-- @if (auth()->user()->profile->photo)
                                <img src="{{ asset('storage\profiles') . '/' . auth()->user()->profile->photo }}"
                                    class="width-icon" alt="">
                            @else
                                <p
                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                    <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                </p>
                            @endif
                            <div
                                class="m-0 justify-content-center align-items-end width-icon change-icon">

                                <span class="fa-fw select-all fas"></span>
                            </div> --}}
                        </div>
                    </div>
                    <div class="ms-3 name">
                        <h5 class="font-bold">
                            {{ auth()->user()->name . ' ' . auth()->user()->lastname }}
                        </h5>
                        <h6 class="text-muted mb-0">
                            {{ Auth::user()->roles[0]->display_name }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                @role('admin')
                    <li class="sidebar-title">Administrador</li>
                    <li class="sidebar-item has-sub {{ request()->is('admin') ? 'active' : '' }}">

                        <a href="{{ route('admin.users.index') }}" class='sidebar-link'>
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                            <span>Administrador</span>
                        </a>

                        <ul class="submenu">

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.organization.index') }}">
                                    <span>Organizacion</span>
                                </a>
                            </li>
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.manager.index') }}">
                                    <span>Managers</span>
                                </a>
                            </li>

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                    <span>Usuarios</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole
                @role('rh')
                    <li class="sidebar-title">Gestion y RH</li>
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Gestion </span>
                        </a>
                        <ul class="submenu ">
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                    <span>Empleados</span>
                                </a>
                            </li>

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.noworkingdays.index') }}">
                                    <span>Dias no laborales</span>
                                </a>
                            </li>

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.communique.show') }}">
                                    <span>Comunicados</span>
                                </a>
                            </li>

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.events.index') }}">
                                    <span>Eventos</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Vacaciones </span>
                            <span
                                class="badge bg-secondary">{{ count(App\Models\Request::where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Pendiente')->get()) }}
                            </span>
                        </a>
                        <ul class="submenu ">
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('request.authorizeRH') }}">
                                    <span>Ver solicitudes</span>
                                    <span
                                        class="badge bg-secondary">{{ count(App\Models\Request::where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Pendiente')->get()) }}
                                    </span>
                                </a>
                            </li>
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('request.reportRequest') }}">
                                    <span>Reportes de ausencias</span>
                                </a>
                            </li>

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.vacations.index') }}">
                                    <span>Vacaciones Disponibles</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Altas y bajas </span>
                        </a>
                        <ul class="submenu ">
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('rh.stadistics') }}">
                                    <span>Estadisticas</span>
                                </a>
                            </li>
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('rh.postulants') }}">
                                    <span>Generar alta</span>
                                </a>
                            </li> 

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('rh.dropUser') }}">
                                    <span>Generar baja</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole('rh')
                <li class="sidebar-title">Menu</li>
                <li class="sidebar-item {{ request()->is('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class='sidebar-link'>
                        <i class="bi bi-house-door-fill"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="sidebar-item has-sub {{ request()->is('about') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Acerca de</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('about_promolife') }}">
                                <span>Promolife</span>
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('about_trade') }}">
                                <span>BH-Trade</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{ request()->is('company') ? 'active' : '' }}">
                    <a href="{{ route('company') }}" class='sidebar-link'>
                        <i class="bi bi-diagram-3-fill"></i>
                        <span>Organigrama</span>
                    </a>
                </li>
                @if (!auth()->user()->hasRole('becario'))
                    <li class="sidebar-item  has-sub {{ request()->is('request') ? 'active' : '' }}">
                        <a href="{{ route('request.index') }}" class='sidebar-link'>
                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                            <span>Permisos y Vacaciones</span>
                            @if (count(auth()->user()->employee->subordinados) > 0)
                                <span
                                    class="badge bg-secondary">{{ count(auth()->user()->employee->requestToAuth()->where('direct_manager_status', 'Pendiente')->get()) }}
                                </span>
                            @endif
                        </a>

                        <ul class="submenu ">

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('request.index') }}">
                                    <span>Mis Solicitudes</span>
                                </a>
                            </li>
                            @if (count(auth()->user()->employee->subordinados) > 0)
                                <li class="submenu-item ">
                                    <a class="dropdown-item" href="{{ route('request.authorizeManager') }}">
                                        <span>Autorizar Solicitudes</span>
                                        <span
                                            class="badge bg-secondary">{{ count(auth()->user()->employee->requestToAuth()->where('direct_manager_status', 'Pendiente')->get()) }}
                                        </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li class="sidebar-item {{ request()->is('directories') ? 'active' : '' }}">
                    <a href="{{ route('directories.index') }}" class='sidebar-link'>
                        <i class="fa fa-address-card" aria-hidden="true"></i>
                        <span>Directorio Interno</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('providers.index') ? 'active' : '' }}">
                    <a href="{{ route('providers.index') }}" class='sidebar-link'>
                        <i class="fa fa-globe" aria-hidden="true"></i>
                        <span>Directorio de Proveedores</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub {{ request()->is('aniversary') ? 'active' : '' }}">
                    <a href="{{ route('aniversary') }}" class='sidebar-link'>
                        <i class="fa fa-birthday-cake" aria-hidden="true"></i>
                        <span>Aniversarios</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('birthday') }}">
                                <span>Cumpleaños</span>
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('aniversary') }}">
                                <span>Aniversarios</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="sidebar-item {{ request()->is('month') ? 'active' : '' }}">
                    <a href="{{ route('month') }}" class='sidebar-link'>
                        <i class="fa fa-trophy" aria-hidden="true"></i>
                        <span>Empleado del Mes</span>
                    </a>
                </li>

                <li class="sidebar-item   has-sub  {{ request()->is('communique') ? 'active' : '' }}">
                    <a href="{{ route('communiques.index') }}" class='sidebar-link'>
                        <i class="fa fa-bell" aria-hidden="true"></i>
                        <span>Comunicados</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('communiques.index') }}">
                                <span>Comunicados generales</span>
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('communiques.department') }}">
                                <span>Comunicados de área</span>
                            </a>
                        </li>
                        @role('rh')
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.communique.show') }}">
                                    <span>Administrar Comunicados</span>
                                </a>
                            </li>
                        @endrole('rh')
                    </ul>
                </li> --}}

                <li class="sidebar-item {{ request()->is('manual') ? 'active' : '' }}">
                    <a href="{{ route('manual.index') }}" class='sidebar-link'>
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <span>Politicas y Procedimientos</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('access') ? 'active' : '' }}">
                    <a href="{{ route('access') }}" class='sidebar-link'>
                        <i class="fa fa-globe" aria-hidden="true"></i>
                        <span>Accesos</span>
                    </a>
                </li>


                {{-- <li class="sidebar-item {{ request()->is('folder') ? 'active' : '' }}">
                    <a href="{{ route('folder') }}" class='sidebar-link'>
                        <i class="fa fa-folder-open" aria-hidden="true"></i>
                        <span>Carpetas</span>
                    </a>
                </li> --}}

                {{-- <li class="sidebar-item {{ request()->is('work') ? 'active' : '' }}">
                    <a href="{{ route('work') }}" class='sidebar-link'>
                        <i class="fa fa-trello" aria-hidden="true"></i>
                        <span>Trello</span>
                    </a>
                </li> --}}


                <!-- <li class="sidebar-item  {{ request()->is('users') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Ver Usuarios</span>
                    </a>
                </li>
                <li class="sidebar-item  {{ request()->is('temas') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Ver Equipos</span>
                    </a>
                </li> -->
                <!-- Authentication Links -->
                @guest
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
