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
                <li class="sidebar-title">Menu</li>
                <li class="sidebar-item {{ request()->is('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class='sidebar-link'>
                        <i class="bi bi-house-door-fill"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                @role('admin')
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
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Gestion </span>
                            <span class="badge bg-secondary">{{ auth()->user()->unreadNotifications->count() }} </span>
                        </a>
                        <ul class="submenu ">
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                    <span>Empleados</span>
                                </a>
                            </li>
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('request.showAll') }}">
                                    <span>Ver solicitudes</span>
                                    <span class="badge bg-secondary">{{ auth()->user()->unreadNotifications->count() }}
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
                                    <span>Vacaciones</span>
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
                @endrole('rh')

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
                        {{-- <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('about_promodreams') }}">
                                <span>Promodreams</span>
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('about_trademarket') }}">
                                <span>Trademarket 57 </span>
                            </a>
                        </li> --}}
                    </ul>
                </li>

                <li class="sidebar-item {{ request()->is('company') ? 'active' : '' }}">
                    <a href="{{ route('company') }}" class='sidebar-link'>
                        <i class="bi bi-diagram-3-fill"></i>
                        <span>Organigrama</span>
                    </a>
                </li>

                <li class="sidebar-item  has-sub {{ request()->is('request') ? 'active' : '' }}">
                    <a href="{{ route('request.index') }}" class='sidebar-link'>
                        <i class="fa fa-pencil-square" aria-hidden="true"></i>
                        <span>Solicitudes</span>
                        @role('employee')
                            <span class="badge bg-secondary">{{ auth()->user()->unreadNotifications->count() }} </span>
                        @endrole('employee')

                        @role('manager')
                            <span class="badge bg-secondary">{{ auth()->user()->unreadNotifications->count() }} </span>
                        @endrole('manager')
                    </a>

                    <ul class="submenu ">

                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('request.index') }}">
                                <span>Mis Solicitudes</span>
                                @role('employee')
                                    <span class="badge bg-secondary">{{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endrole('employee')

                            </a>
                        </li>
                        @if (count(auth()->user()->employee->subordinados) > 0)
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('request.authorizeManager') }}">
                                    <span>Autorizar Solicitudes</span>
                                    <span
                                        class="badge bg-secondary">{{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="sidebar-item {{ request()->is('directories') ? 'active' : '' }}">
                    <a href="{{ route('directories.index') }}" class='sidebar-link'>
                        <i class="fa fa-address-card" aria-hidden="true"></i>
                        <span>Directorio</span>
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

                <li class="sidebar-item {{ request()->is('month') ? 'active' : '' }}">
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
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('admin.communique.show') }}">
                                <span>Comunicados</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ request()->is('manual') ? 'active' : '' }}">
                    <a href="{{ route('manual.index') }}" class='sidebar-link'>
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <span>Manuales</span>
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
                @else
                    <li class="sidebar-item  has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="submenu ">
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <span>Mi cuenta</span>
                                </a>
                            </li>
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('Cerrar sesion') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
