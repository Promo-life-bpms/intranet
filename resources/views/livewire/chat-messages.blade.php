<div>
    <div class="card-chat card my-0 mx-3" style="box-shadow: 0px 18px 10px 0px black">
        <div class="d-flex flex-row justify-content-between adiv p-3 text-white">
            <i class="bi bi-chevron-down zoom ease" style="font-size: 20px" wire:click="collapseChat"></i>
            {{ $user->name . ' ' . $user->lastname }}
            <i class="fas fa-times zoom ease" style="font-size: 20px" wire:click="closeChat"></i>
        </div>
        <div class="style-1 {{ !$ChatCollapse ? 'd-none' : '' }}">
            <div style="height: 300px; overflow-y: auto" {{-- id="formChat" --}} class="style-1">
                <div>
                    <div class="d-flex flex-row p-2" {{-- :class="userId == mensaje.transmitter_id ?
                            'justify-content-start' :
                            'justify-content-end'" --}}>
                        <img {{-- v-if="userId == mensaje.transmitter_id" --}}
                            src="https://cdn-icons.flaticon.com/png/512/2550/premium/2550383.png?token=exp=1656952747~hmac=ed262840f58c4e24269fa611714af05a"
                            style="width: 25px; height: 25px"
                            class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon" />

                        <div class="chat ml-2 p-1" {{-- :class="userId == mensaje.transmitter_id ? 'bg-white ml-2 p-2' : 'chat mr-2 p-2'" --}}>
                            <span style="font-size: 12px">{{-- {{mensaje . message }} --}}</span>
                            <div class="d-flex text" {{-- :class="userId == mensaje.transmitter_id ?
                                    'justify-content-start' :
                                    'justify-content-end'" --}}>
                                <span>{{-- {{ mensaje . created_at . substring(11, 16) }} --}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @livewire('chat-form')
            {{-- <ChatForm :authId="authId" :userId="userId" v-on:cerrarChat="cerrarChat" /> --}}
        </div>
    </div>
</div>


<style scoped>
    @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap");

    body {
        background: #eeeeee;
        font-family: "Roboto", sans-serif;
    }

    .adiv {
        background: #61a5b5;
        border-radius: 15px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        font-size: 12px;
        height: 46px;
    }

    .chat {
        border: none;
        background: #e2fbff;
        font-size: 10px;
        border-radius: 20px;
    }

    .bg-white {
        border: 1px solid #e7e7e9;
        font-size: 10px;
        border-radius: 20px;
    }

    .zoom:hover {
        transform: scale(1.3);
        transition: 0.5s;
    }

    .ease {
        transition: 1s ease-out;
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
