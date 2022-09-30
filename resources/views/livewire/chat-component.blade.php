<div>
    <div class="contenedor position-fixed">
        <div class="d-flex flex-row-reverse align-items-end">

            <input type="checkbox" id="click" wire:click="collapseListUsers">
            <label class="buble" for="click">
                <i class="fab fa-facebook-messenger"></i>
                <i class="fas fa-times"></i>
            </label>
            <input type="checkbox" id="click" wire:click="collapseListUsers">
            <label class="buble" for="click">
                <i class="fab fa-facebook-messenger"></i>
                <i class="fas fa-times"></i>
            </label>
            <div class="wrapper">
                <div class="head-text">
                    Contactos</div>

                <div style="max-height: 250px; overflow-y: scroll" id="style-3">
                    @foreach ($users as $user)
                        <div class="d-flex flex-row p-2 usuario" wire:click="openChat({{ $user->id }})"
                            style="cursor: pointer">

                            <div class="img_cont">
                                <img src="{{ $user->image === null || $user->image === ''
                                    ? 'https://images.vexels.com/media/users/3/136558/isolated/lists/43cc80b4c098e43a988c535eaba42c53-icono-de-usuario-de-persona.png'
                                    : $user->image }}"
                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icons"
                                    style="width: 30px; height: 30px" />
                                @if ($user->isOnline)
                                    <span class="online_icon"></span>
                                @endif
                            </div>
                            <p class="m-0 mx-1"> {{ $user->name }}</p>

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
    <script>
        window.addEventListener('chatStorage', event => {
            const listaChats = event.detail.chat;
            console.log(listaChats);
            localStorage.setItem("statusChat", JSON.stringify(listaChats));

        })

        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('livewire:load', function() {
                const chatStatus = JSON.parse(localStorage.getItem("statusChat"));
                if(chatStatus){
                    @this.listaChatsAbiertos = chatStatus;
                }

                console.log(chatStatus);

            })

        })
    </script>
    <style scoped>
        @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap");

        .contenedor {
            z-index: 100;
            right: 300px;
            bottom: 0px;
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

        #style-3::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px #3f3f3f4d;
            background-color: #F5F5F5;
        }

        #style-3::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }

        #style-3::-webkit-scrollbar-thumb {
            background-color: #3f3f3f4d;
        }

        #click {
            display: none;
        }

        .buble {
            z-index: 100;
            position: fixed;
            right: 30px;
            bottom: 20px;
            height: 55px;
            width: 55px;
            background: -webkit-linear-gradient(left, #72c3d6, #0390f5);
            text-align: center;
            line-height: 55px;
            border-radius: 50px;
            font-size: 30px;
            color: #fff;
            cursor: pointer;
        }

        .buble i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.4s ease;
        }

        .buble i.fas {
            opacity: 0;
            pointer-events: none;
        }

        #click:checked~.buble i.fas {
            opacity: 1;
            pointer-events: auto;
            transform: translate(-50%, -50%) rotate(180deg);
        }

        #click:checked~.buble i.fab {
            opacity: 0;
            pointer-events: none;
            transform: translate(-50%, -50%) rotate(180deg);
        }

        #click:checked~.wrapper {
            opacity: 1;
            bottom: 85px;
            pointer-events: auto;
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

        .wrapper .head-text {
            line-height: 60px;
            color: #fff;
            border-radius: 15px 15px 0 0;
            padding: 0 20px;
            font-weight: 500;
            font-size: 20px;
            background: -webkit-linear-gradient(left, #72c3d6, #0390f5);
        }
    </style>
</div>
