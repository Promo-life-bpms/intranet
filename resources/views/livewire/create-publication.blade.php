<div>
    <link rel="stylesheet" href="{{ asset('assets\vendors\quill\quill.bubble.css') }}">
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <div class="">
                    @if (auth()->user()->image == null)
                        <a style="color: inherit;" href="{{ route('profile.index') }}">
                            <p
                                class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                            </p>
                        </a>
                    @else
                        <a href="{{ route('profile.index') }}">
                            <img style="width: 55px; height:55px; object-fit: cover;" class="rounded-circle"
                                src="{{ asset(auth()->user()->image) }}">
                        </a>
                    @endif
                </div>

                <form wire:submit.prevent="savePublication" class="form-publicar ml-2 flex-grow-1">
                    @if (session('errorData'))
                        <div class="text-danger" id="messageError">
                            {{ session('errorData') }}
                        </div>
                    @endif

                    <div>
                        <div id="bubble" class="form-control px-1">
                            <p>Que estas pensando!</p>
                        </div>
                        <input type="hidden" id="content-publication" name="content_publication">
                    </div>
                    <div class="collapse" id="collapseExample">
                        <div class="form-group" id="itemsElement">
                            <label for="imagen">
                            </label>
                            <div id="dropzoneItems" class="dropzone form-control text-center" style="height: auto;">
                            </div>
                            <input type="hidden" name="items" id="items">
                            @error('items')
                                <div class="text-danger">
                                    {{ $message }}</div>
                            @enderror
                            <p id="error"></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between" class="public_items">
                        <div class="mt-3">
                            <button class="boton" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-file-earmark-image"></i>
                            </button>
                        </div>
                        <div class=" mt-3">
                            <input type="submit" class="boton" value="Publicar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets\vendors\quill\quill.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            var bubble = new Quill("#bubble", {
                theme: "bubble",
            })
            document.querySelector('#content-publication').value = document.querySelector('#bubble .ql-editor')
                .innerHTML
            document.querySelector('.ql-editor').addEventListener('keyup', () => {
                document.querySelector('#content-publication').value = document.querySelector(
                        '#bubble .ql-editor')
                    .innerHTML

            })
        })
    </script>
</div>
