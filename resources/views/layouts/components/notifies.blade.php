@if (count(auth()->user()->notifications) == 0)
    <li>
        <a class="m-0 dropdown-item">No hay notificaciones</a>
    </li>
@else
    { @foreach (auth()->user()->unreadNotifications as $notification)
        <li>
            <div class="dropdown-item m-0">
                @switch($notification->type)
                    @case('App\Notifications\MessageNotification')
                        <p class="m-0">{{ $notification->data['emisor'] }}</p>
                        <p class="m-0">{{ Str::limit($notification->data['message'], 30) }}</p>
                    @break
                    @default
                @endswitch
                <p class="m-0"><a
                        href="{{ route('message.markAsRead', ['notification' => $notification->id]) }}">Marcar
                        como
                        leido</a></p>

            </div>
        </li>
    @endforeach
@endif
