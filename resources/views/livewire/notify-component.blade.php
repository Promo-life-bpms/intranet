<div>
    @if ($active)
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <svg class="bi bell" fill="currentColor">
                <use xlink:href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.svg#bell-fill') }}" />
            </svg>
            <span class="badge-number position-absolute translate-middle badge rounded-pill bg-danger"
                style="font-size: 0.7rem">
                {{ $countNotifications }}
            </span>
        </a>
        <ul class="dropdown-menu" style="max-height: 500px; overflow-y: scroll;"
            aria-labelledby="navbarDropdownMenuLink">
            @if ($countNotifications == 0)
                <li>
                    <a class="m-0 dropdown-item">No hay notificaciones</a>
                </li>
            @else
                @foreach ($unreadNotifications as $notification)
                    <li>
                        <div class="dropdown-item m-0">

                            <h6 class="mb-1">{{ Str::limit($notification->data['transmitter_name'], 28) }}</h6>
                            @switch($notification->type)
                                @case('App\Notifications\MessageNotification')
                                    <p class="m-0">{{ Str::limit($notification->data['message'], 30) }}</p>
                                @break

                                @default
                            @endswitch
                            </a>
                            <p class="m-0 d-flex justify-content-between">
                                <a href="{{ route('message.markAsRead', ['notification' => $notification->id]) }}">Marcar
                                    como
                                    leido</a>
                                {{-- <button wire:click="markAsRead({{ $notification->id }})">Marcar como
                                    leido</button> --}}
                            <div>
                                <p class="m-0">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            </p>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    @endif
</div>
