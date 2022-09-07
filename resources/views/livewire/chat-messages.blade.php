<div>

    <div class="animate__animated animate__bounceIn wrapper1 my-0 mx-2">
        <div class="head-text d-flex">
            {{ $user->name }}
            <div class="d-flex" style="align-items: center">

                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-dash-lg button-chat" viewBox="0 0 16 16" wire:click="collapseChat"
                    style="cursor: pointer;">
                    <path fill-rule="evenodd" d="M2 8a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 8Z" />
                </svg>
                <i class="fas fa-times " style="cursor: pointer; font-size: 20px"
                    wire:click="cerrarChat({{ $user->id }})"></i>
            </div>
        </div>
        <div class="cont">
            <div class="{{ !$ChatCollapse ? 'd-none' : '' }}">
                <div style="height: 300px; overflow-y: auto" class="style-3" id="formChat{{ $idUser }}">
                    {{-- <button wire:click="messageScroll">Scroll</button> --}}
                    @foreach ($mensajesEnviados as $mensaje)
                        <div
                            class="d-flex flex-row p-2
                            {{ $mensaje->transmitter_id == $user->id ? 'justify-content-start' : 'justify-content-end' }}">
                            <img src="{{ $user->image === null || $user->image === ''
                                ? 'https://www.kindpng.com/picc/m/269-2697881_computer-icons-user-clip-art-transparent-png-icon.png'
                                : $user->image }}"
                                style="width: 25px; height: 25px"
                                class="rounded-circle border border-primary m-0  {{ $mensaje->transmitter_id == $user->id ? 'd-flex' : 'd-none' }} justify-content-center align-items-center width-icon" />

                            <div
                                class="{{ $mensaje->transmitter_id == auth()->user()->id ? 'message-white ml-2 p-2' : 'chat mr-2 p-2' }}">
                                <span style="font-size: 12px">{{ $mensaje->message }}</span>
                                <div
                                    class="{{ $mensaje->transmitter_id == $user->id ? 'justify-content-start' : 'justify-content-end' }} ">
                                </div>
                                <p class="date">

                                    @php
                                        $dateCreate = \Carbon\Carbon::parse($mensaje->created_at);
                                    @endphp

                                    {{ $dateCreate->diffForHumans() }}
                                </p>

                            </div>

                        </div>
                    @endforeach
                </div>
                @livewire('chat-form', ['userId' => $user->id])
            </div>
        </div>

    </div>
    <script>
        let id = "{{ $idUser }}"
        /* alert("formChat" + id) */
        document.addEventListener('messageNew', () => {

            const objDiv = document.getElementById("formChat" + id);
            objDiv.scrollTop = objDiv.scrollHeight;
            console.log("evento recibido");


        })

        /* const element = document.querySelector('.my-element');
        element.classList.add('animate__animated', 'animate__bounceIn'); */
    </script>

    <style scoped>
        .cont {
            width: 300px;
            display: flex;
            flex-direction: column;
        }

        .message-white {

            border-radius: 20px;
            background: #eceff1;
            color: #000000;
            text-align: right;
        }

        .chat {
            border-radius: 20px;
            background: #00b0ff;
            color: #fff;
        }

        .wrapper1 {
            background: rgb(255, 255, 255);
            border-radius: 15px;
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);

        }

        .wrapper1 .head-text {
            line-height: 60px;
            color: #fff;
            border-radius: 15px 15px 0 0;
            padding: 0 20px;
            font-weight: 500;
            font-size: 15px;
            justify-content: space-between;
            background: -webkit-linear-gradient(left, #72c3d6, #0390f5);

        }

        .style-3::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px #3f3f3f4d;
            background-color: #F5F5F5;
        }

        .style-3::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }

        .style-3::-webkit-scrollbar-thumb {
            background-color: #3f3f3f4d;
        }

        .button-chat {
            margin-right: 10px;
        }

        .date {
            margin-bottom: 0;
            margin-top: 0;
            font-size: 10px;
        }
    </style>
</div>
