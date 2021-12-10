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
                        <div class="card-photo">
                            <p
                                class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                            </p>
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
                            {{ Auth::user()->roles->pluck('name') }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>


        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                @can('admin')
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
                            {{-- <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.employee') }}">
                                    <span>Empleados</span>
                                </a>
                            </li> --}}

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

                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('admin.roles.index') }}">
                                    <span>Roles</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

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
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('about_promodreams') }}">
                                <span>Promodreams</span>
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('about_trademarket') }}">
                                <span>Trademarket 57 </span>
                            </a>
                        </li>
                    </ul>
                </li>
                @can('rh')
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-info-circle-fill"></i>
                            <span>Gestion de ausencias</span>
                        </a>
                        <ul class="submenu ">
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('request.showAll') }}">
                                    <span>Ver solicitudes</span>
                                </a>
                            </li>
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('about_trade') }}">
                                    <span>Reportes de ausencias</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li class="sidebar-item {{ request()->is('company') ? 'active' : '' }}">
                    <a href="{{ route('company') }}" class='sidebar-link'>
                        <i class="bi bi-diagram-3-fill"></i>
                        <span>Organigrama</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('admin.contacts') ? 'active' : '' }}">
                    <a href="{{ route('admin.contacts.index') }}" class='sidebar-link'>
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

                <li class="sidebar-item  has-sub {{ request()->is('communique') ? 'active' : '' }}">
                    <a href="{{ route('communiques.index') }}" class='sidebar-link'>
                        <i class="fa fa-bell" aria-hidden="true"></i>
                        <span>Comunicados</span>
                    </a>
                    <ul class="submenu ">

                        @can('admin.rh.sistemas')
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('communiques.create') }}">
                                    <span>Crear comunicados</span>
                                </a>
                            </li>
                        @endcan
                        <li class="submenu-item ">
                            <a class="dropdown-item" href="{{ route('communiques.index') }}">
                                <span>Ver comunicados</span>
                            </a>
                        </li>
                        @can('admin.rh.sistemas')
                            <li class="submenu-item ">
                                <a class="dropdown-item" href="{{ route('communiques.index') }}">
                                    <span>Administrar comunicados</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="sidebar-item {{ request()->is('manual') ? 'active' : '' }}">
                    <a href="{{ route('manual') }}" class='sidebar-link'>
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

                <li class="sidebar-item {{ request()->is('folder') ? 'active' : '' }}">
                    <a href="{{ route('folder') }}" class='sidebar-link'>
                        <i class="fa fa-folder-open" aria-hidden="true"></i>
                        <span>Carpetas</span>
                    </a>
                </li>

                <li class="sidebar-item  has-sub {{ request()->is('request') ? 'active' : '' }}">
                    <a href="{{ route('request.index') }}" class='sidebar-link'>
                        <i class="fa fa-pencil-square" aria-hidden="true"></i>
                        <span>Solicitudes</span>
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
                                </a>
                            </li>
                        @endif
                    </ul>

                </li>

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
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
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
