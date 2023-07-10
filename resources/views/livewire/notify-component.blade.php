<div>
    <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdownMenuLink" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <svg class="bi bell" fill="currentColor">
            <use xlink:href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.svg#bell-fill') }}" />
        </svg>
        <span class="badge-number position-absolute translate-middle badge rounded-pill bg-danger"
            style="font-size: 0.7rem">
            {{ $countNotifications }}
        </span>
    </a>
    <ul class="dropdown-menu shadow" style="max-height: 600px; max-width: 400px; overflow-y: auto;"
        aria-labelledby="navbarDropdownMenuLink">
        @if ($countNotifications == 0)
            <li>
                <a class="m-0 dropdown-item px-3">No hay notificaciones</a>
            </li>
        @else
            @foreach ($unreadNotifications as $notification)
                <li>
                    <div class="dropdown-item m-0 px-3">
                        <div>
                            @switch($notification->type)
                                @case('App\Notifications\MessageNotification')
                                    <strong>{{ Str::limit($notification->data['transmitter_name'], 28) }}</strong>
                                    <p class="my-0">ha enviado un mensaje</p>
                                @break

                                @case('App\Notifications\CreateRequestNotification')
                                    <b>{{ $notification->data['emisor_name'] }}</b>
                                    <p class="my-0">ha creado una solicitud del tipo</p>
                                    <p class="my-0">{{ $notification->data['type'] }}</p>
                                @break

                                @case('App\Notifications\ManagerResponseRequestNotification')
                                    <b>{{ $notification->data['emisor_name'] }}</b>
                                    <p class="my-0">ha respondido a tu solicitud</p>
                                    <p class="my-0">Estado: {{ $notification->data['status'] }}</p>
                                @break

                                @case('App\Notifications\RHResponseRequestNotification')
                                    <b>{{ $notification->data['emisor_name'] }}</b>
                                    <p class="my-0">ha respondido a tu solicitud</p>
                                    <p class="my-0">Estado: {{ $notification->data['status'] }}</p>
                                @break

                                @case('App\Notifications\AlertRequestToAuth')
                                    <b>No has autorizado la solicitud de:</b>
                                    <p class="my-0">{{ $notification->data['emisor_name'] }}</p>
                                    <p class="my-0">{{ $notification->data['type'] }}</p>
                                @break

                                @case('App\Notifications\AlertRequestToRH')
                                    <b>Tienes solicitudes por aprobar:</b>
                                    <p class="my-0">Siendo departamento de RRHH</p>
                                @break

                                @case('App\Notifications\ManagerResponseRequestToRHNotification')
                                    <b>{{ $notification->data['user_name'] }}</b>
                                    <p class="my-0">Ha autorizado una solicitud de:</p>
                                    <p class="my-0">{{ $notification->data['emisor_name'] }}</p>
                                @break

                                @case('App\Notifications\StatuSoporteFinalizadoNotification')
                                    <b>{{ $notification->data['data']['name'] }}</b>
                                    <p class="my-0">Finalizo el ticket :</p>
                                    <p class="my-0"> {{ $notification->data['data']['name_ticket'] }}</p>
                                @break

                                @case('App\Notifications\SoporteNotification')
                                    <b>{{ $notification->data['data']['name'] }}</b>
                                    <p class="my-0">Envio un ticket :</p>
                                    <p class="my-0">{{ $notification->data['data']['name_ticket'] }}</p>
                                @break

                                @case('App\Notifications\EditarTicketNotification')
                                    <b>{{ $notification->data['data']['name'] }}</b>
                                    <p class="my-0">Edito ticket :</p>
                                    <p class="my-0">{{ $notification->data['data']['name_ticket'] }}</p>
                                @break

                                @case('App\Notifications\MessageSoporteNotification')
                                    <b>{{ $notification->data['data']['name'] }}</b>
                                    <p class="my-0">Envío un mensaje </p>
                                    <p>Ticket :{{ $notification->data['data']['name_ticket'] }}</p>
                                @break

                                @case('App\Notifications\SolucionSoporteNotification')
                                    <b>{{ $notification->data['data']['name'] }}</b>
                                    <p class="my-0">Envio solucion </p>
                                    <p>Ticket :{{ $notification->data['data']['name_ticket'] }}</p>
                                @break

                                @case('App\Notifications\StatusEnProcesoSoporteNotification')
                                    <b>{{ $notification->data['data']['name'] }}</b>
                                    <p class="my-0">Vio tu Ticket :</p>
                                    <p class="my-0">{{ $notification->data['data']['name_ticket'] }}</p>
                                @break

                                @case('App\Notifications\MessageSoporteSolutionNotification')
                                    <b>{{ $notification->data['data']['name'] }}</b>
                                    <p class="my-0">Envio un mensaje </p>
                                    <p class="my-0">Ticket :{{ $notification->data['data']['name_ticket'] }}</p>
                                @break
                                @case('App\Notifications\ReasignacionTicketSoporte')
                                    <b>{{ $notification->data['data']['name'] }}</b>
                                    <p class="my-0">Te reasigno un ticket </p>
                                    <p class="my-0">Ticket : {{ $notification->data['data']['name_ticket'] }}</p>
                                @break
                                @default
                            @endswitch
                        </div>
                        <div class="m-0 d-flex justify-content-between">
                            <p class="m-0" style="font-size: 12px">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                            <a href="{{ route('message.markAsRead', ['notification' => $notification->id]) }}"
                                class="btn btn-sm " style=""><svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-x-circle"
                                    viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                </svg></a>
                        </div>
                    </div>
                </li>
            @endforeach
        @endif
    </ul>
</div>
