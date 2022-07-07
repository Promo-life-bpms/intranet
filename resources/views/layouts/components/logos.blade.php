<div class="contenedor-logo  rounded p-3" style="margin-top: -87px;">
    <ul class="logos p-3"
        style="background: #ffffff; box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;
    border-radius: 10px;
    ">
        <li class="pl-4 m-0"><a href="#"><img style="width: 70px" src="{{ asset('/img/bhtrade.png') }}"
                    alt="bhtrade"></a> </li>
        <li class="p-0 m-0"><a href="#"><img style="width: 150px;" src="{{ asset('/img/promolife.png') }}"
                    alt="promolife"></a>
        </li>
        <li class="p-0 m-0"><a href="#"><img style="width: 70px;" src="{{ asset('/img/promodreams.png') }}"
                    alt="promodreams"></a>
        </li>
        <li class="p-0 m-0"><a href="#"><img style="width: 70px;" src="{{ asset('/img/trademarket.png') }}"
                    alt="trademarket"></a>
        </li>
        <li class="pr-4 m-0" id="app">
            <notification-bell></notification-bell>
            <chat-component :authId="{{ auth()->user()->id }}"></chat-component>
            <notify :auth-id={{ auth()->user()->id }}></notify>
        </li>
    </ul>
</div>
