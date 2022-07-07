<div class="d-flex align-items-center my-2">
    @if (auth()->user()->image == null)
        <a style="color: inherit;" href="{{ route('profile.index') }}" class="mr-1">
            <p
                class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
            </p>
        </a>
    @else
        <a href="{{ route('profile.index') }}" class="mr-1">
            <img style="width: 35px; height:35px; object-fit: cover;" class="rounded-circle"
                src="{{ asset(auth()->user()->image) }}">
        </a>
    @endif
    {{-- TODO: Evitar que el DOM se modifique --}}
    <input type="text" id="comment" name="comment"
        wire:model="comment"
        class="form-control px-2 flex-grow-1  @error('comment') is-invalid @enderror"
        placeholder="Escribe tu comentario...">
    <button class="boton" wire:click="comentar">
        >
    </button>
</div>
