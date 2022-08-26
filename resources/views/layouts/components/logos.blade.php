<div class="contenedor-logo  rounded pt-3 px-1">
    <ul class="logos px-1 py-2"
        style="background: #ffffff; box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;
    border-radius: 10px;
    ">
        {{-- Menu Hamburguesa --}}
        <header class="d-xl-none">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <li class="pl-4 m-0"><img style="width: 45px" src="{{ asset('/img/bhtrade.png') }}" alt="bhtrade"> </li>
        <li class="p-0 m-0"><img style="width: 100px;" src="{{ asset('/img/promolife.png') }}" alt="promolife">
        </li>
        <li class="p-0 m-0"><img style="width: 45px;" src="{{ asset('/img/promodreams.png') }}" alt="promodreams">
        </li>
        <li class="p-0 m-0"><img style="width: 45px;" src="{{ asset('/img/trademarket.png') }}" alt="trademarket">
        </li>
        <li class="d-none" id="app">
            <notify :auth-id={{ auth()->user()->id }}></notify>
        </li>
        <li class="pr-4 m-0 d-flex align-items-center">
            {{-- <notification-bell></notification-bell> --}}
            {{-- <chat-component :authId="{{ auth()->user()->id }}"></chat-component> --}}
            @livewire('notify-component')
            @livewire('chat-component')

            {{-- <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                data-bs-toggle="dropdown" aria-expanded="false"> --}}
            <div class="dropdown">
                <div class="d-flex align-items-center nav-link dropdown-toggle px-0" id="navbarDropdownMenuLink"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <h6 class="font-bold">
                        {{ auth()->user()->name }}
                        <i class="bi bi-caret-down-fill"></i>
                    </h6>
                </div>
                {{-- </a> --}}
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                            <span>Mi cuenta</span>
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            {{ __('Cerrar sesion') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>
