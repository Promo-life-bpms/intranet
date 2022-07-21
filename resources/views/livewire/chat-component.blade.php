<div>
    <div class="d-flex flex-row-reverse align-items-end">
        <input type="checkbox" id="click" wire:click="collapseListUsers">
        <label for="click">
            <i class="fab fa-facebook-messenger"></i>
            <i class="fas fa-times"></i>
        </label>
        <div class="wrapper">
            <div class="head-text">
                Contactos</div>
            <div class="chat-box">
                <div class="desc-text">
                    <div style="max-height: 250px; overflow-y: scroll" id="style-3"
                        class="{{-- style-1 --}} {{ !$listUsersCollapse ? 'd-none' : '' }}">
                        @foreach ($users as $user)
                            <div class="d-flex flex-row p-2 usuario" wire:click="openChat({{ $user->id }})">

                                <div class="img_cont">
                                    <img src="https://www.kindpng.com/picc/m/269-2697881_computer-icons-user-clip-art-transparent-png-icon.png"
                                        class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icons"
                                        style="width: 30px; height: 30px" />
                                    @if ($user->isOnline)
                                        <span class="online_icon"></span>
                                    @endif
                                </div>
                                <p> {{ $user->name . ' ' . $user->lastname }}</p>

                            </div>
                        @endforeach
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 18 18">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </span>
                        <input type="text" class="form-control" placeholder="Buscar contacto" wire:model="search" />
                    </div>
                </div>
            </div>
        </div>
        @foreach ($listaChatsAbiertos as $chatUser)
            @livewire('chat-messages', ['idUser' => $chatUser], key($chatUser))
        @endforeach
    </div>

    <style scoped>
        @import url("https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap");

        #click {
            display: none;
        }

        label {
            z-index: 100;
            position: fixed;
            right: 30px;
            bottom: 20px;
            height: 55px;
            width: 55px;
            background: -webkit-linear-gradient(left, #72c3d6, #10594e);
            text-align: center;
            line-height: 55px;
            border-radius: 50px;
            font-size: 30px;
            color: #fff;
            cursor: pointer;
        }

        label i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.4s ease;
        }

        label i.fas {
            opacity: 0;
            pointer-events: none;
        }

        #click:checked~label i.fas {
            opacity: 1;
            pointer-events: auto;
            transform: translate(-50%, -50%) rotate(180deg);
        }

        #click:checked~label i.fab {
            opacity: 0;
            pointer-events: none;
            transform: translate(-50%, -50%) rotate(180deg);
        }

        .wrapper {
            z-index: 100;
            position: fixed;
            right: 30px;
            bottom: 0px;
            max-width: 350px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
            opacity: 0;
            pointer-events: none;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        #click:checked~.wrapper {
            opacity: 1;
            bottom: 85px;
            pointer-events: auto;
        }

        .wrapper .head-text {
            line-height: 60px;
            color: #fff;
            border-radius: 15px 15px 0 0;
            padding: 0 20px;
            font-weight: 500;
            font-size: 20px;
            background: -webkit-linear-gradient(left, #72c3d6, #10594e);
        }

        .wrapper .chat-box {
            padding: 1px;
            width: 100%;
        }

        .chat-box .desc-text {
            color: #515365;
            text-align: center;
            line-height: 25px;
            font-size: 17px;
            font-weight: 500;
        }

        .img_cont {
            position: relative;
            height: 40px;
            width: 40px;
        }

        #style-3::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }

        #style-3::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }

        #style-3::-webkit-scrollbar-thumb {
            background-color: #181818;
        }
    </style>
</div>

{{-- <div>
    <div class="contenedor position-fixed">
        <div class="d-flex flex-row-reverse align-items-end">

            <div class="card-chat card my-0" style="box-shadow: 0px 18px 10px 0px black">
                <div class="d-flex flex-row justify-content-between adiv p-3 text-white">
                    <i class="bi bi-people-fill zoom ease" style="font-size: 20px" wire:click="collapseListUsers"></i>
                    <span class="pb-3">Contactos conectados</span>
                </div>
                <div style="max-height: 300px; overflow-y: scroll"
                    class="style-1 {{ !$listUsersCollapse ? 'd-none' : '' }}">
                    @foreach ($users as $user)
                        <div class="d-flex flex-row p-2 usuario" wire:click="openChat({{ $user->id }})">

                            <div class="img_cont">
                                <img src="https://www.kindpng.com/picc/m/269-2697881_computer-icons-user-clip-art-transparent-png-icon.png"
                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icons"
                                    style="width: 30px; height: 30px" />
                                @if ($user->isOnline)
                                    <span class="online_icon"></span>
                                @endif
                            </div>
                            <p> {{ $user->name . ' ' . $user->lastname }}</p>

                        </div>
                    @endforeach
                </div>
                <div class="input-group">
                    <span class="input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 18 18">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </span>
                    <input type="text" class="form-control" placeholder="Buscar contacto" wire:model="search" />
                </div>

            </div>
            @foreach ($listaChatsAbiertos as $chatUser)
                @livewire('chat-messages', ['idUser' => $chatUser], key($chatUser))
            @endforeach
        </div>
    </div>
    <style scoped>
        @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap");

        .card-chat {
            width: 300px;
            border: none;
            border-radius: 15px;
        }

        .adiv {
            background: #61a5b5;
            border-radius: 15px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            font-size: 12px;
            height: 46px;
        }

        .form-control {
            border: 1px solid #9e9e9e;
            font-size: 13px;
        }

        .form-control::placeholder {
            font-size: 12px;
            color: #6c6c6c;
        }

        .contenedor {
            z-index: 100;
            right: 1px;
            bottom: 1px;
        }

        .online_icon {
            position: absolute;
            height: 15px;
            width: 15px;
            background-color: #4cd137;
            border-radius: 50%;
            bottom: 0.2em;
            right: 0.4em;
            border: 1.5px solid white;
        }

        .img_cont {
            position: relative;
            height: 35px;
            width: 40px;
        }

        .zoom:hover {
            transform: scale(1.3);
            transition: ease-in-out 0.5s;
        }

        .ease {
            transition: 1s ease-out;
        }

        .usuario:hover {
            background: #eaeaea;
        }

        .style-1::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: #f5f5f5;
        }

        .style-1::-webkit-scrollbar {
            width: 12px;
            background-color: #f5f5f5;
        }

        .style-1::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #555;
        }
    </style>
</div> --}}
