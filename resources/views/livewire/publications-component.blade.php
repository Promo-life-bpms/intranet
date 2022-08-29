<div>
    @if (count($publications) <= 0)
        <p>No hay Publicaciones</p>
    @else
        @foreach ($publications as $publication)
            <div class="card mb-4 shadow-sm" style="border-radius: 20px" wire:key="pub-{{ $publication->id }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="imagen px-1">
                                <div class="avatar avatar-xl">
                                    <div class="card-photo" style="width: 40px; height:40px;">
                                        @if (auth()->user()->image == null)
                                            <a style="color: inherit;"
                                                href="{{ route('profile.view', ['prof' => $publication->user_id]) }}">
                                                <p
                                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                    <span>{{ substr($publication->user->name, 0, 1) . substr($publication->user->lastname, 0, 1) }}</span>
                                                </p>
                                            </a>
                                        @else
                                            <a style="color: inherit;"
                                                href="{{ route('profile.view', ['prof' => $publication->user_id]) }}">
                                                <img style="width: 100%; height:100%; object-fit: cover;"
                                                    src="{{ asset($publication->user->image) }}">
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="m-0" style="font-weight: bold">
                                    {{ $publication->user->name . ' ' . $publication->user->lastname }}
                                </p>
                                <p class="m-0">
                                    {{ $publication->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="dropdown-center">
                                <button class="btn boton dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path
                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Editar
                                        </a></li>

                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            href="#">Eliminar</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar publicacion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('publications.store') }}"
                                        class="form-publicar ml-2 flex-grow-1" id="formCreate" method="post">
                                        {!! csrf_field() !!}
                                        @method('PATCH')
                                        @if (session('errorData'))
                                            <div class="text-danger" id="messageError">
                                                {{ session('errorData') }}
                                            </div>
                                        @endif
                                        <textarea id="exampleFormControlTextarea1" class="form-control" name="content_publication" style="height: 55px">{{ $publication->content_publication }}</textarea>
                                        <div class="collapse" id="collapseExample">
                                            <div class="form-group" id="itemsElement">
                                                <label for="imagen">
                                                </label>
                                                <div id="dropzoneItems" class="dropzone form-control text-center"
                                                    style="height: auto;">
                                                </div>
                                                <input type="hidden" name="items" id="items"
                                                    value="{{ old('items') }}">
                                                @error('items')
                                                    <div class="text-danger">
                                                        {{ $message }}</div>
                                                @enderror
                                                <p id="error"></p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between" class="public_items">
                                            {{-- <div class="mt-3">
                                                <button class="boton" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseExample" aria-expanded="false"
                                                    aria-controls="collapseExample">
                                                    <i class="bi bi-file-earmark-image"></i>
                                                </button>
                                            </div> --}}
                                            <div class=" mt-3">
                                                <input type="submit" class="boton" value="Guardar"></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 ">
                        {{ $publication->content_publication }}
                    </p>
                    @if (count($publication->files) > 0)
                        <div class="row" style="overflow:hidden;">
                            @if (count($publication->files) == 1)
                                @foreach ($publication->files as $item)
                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                        wire:key="img-{{ $publication->id }}{{ $item->id }}"
                                        data-lightbox="photos.{{ $publication->id }}" style="height: 600px;">
                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                            class="rounded shadow-sm"
                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                    </a>
                                @endforeach
                            @elseif (count($publication->files) == 2)
                                @foreach ($publication->files as $item)
                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                        wire:key="img-{{ $publication->id }}{{ $item->id }}"
                                        data-lightbox="photos.{{ $publication->id }}" style="height: 300px;"
                                        class="col-md-6">
                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                            class="rounded shadow-sm"
                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                    </a>
                                @endforeach
                            @elseif (count($publication->files) == 3)
                                @foreach ($publication->files as $item)
                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                        wire:key="img-{{ $publication->id }}{{ $item->id }}"
                                        data-lightbox="photos.{{ $publication->id }}" style="height: 200px;"
                                        class="col-md-4">
                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                            class="rounded shadow-sm"
                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                    </a>
                                @endforeach
                            @elseif (count($publication->files) > 3)
                                @foreach ($publication->files as $item)
                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                        wire:key="img-{{ $publication->id }}{{ $item->id }}"
                                        data-lightbox="photos.{{ $publication->id }}"
                                        style="height: {{ $loop->iteration > 4 ? '0' : '200' }}px;" class="col-md-3">
                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                            class="rounded shadow-sm {{ $loop->iteration > 3 && count($publication->files) > 4 ? 'd-none' : '' }}"
                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                        @if ($loop->iteration == 4 && count($publication->files) > 4)
                                            <div class="w-100 h-100 d-flex justify-content-center align-items-center mas-fotos"
                                                style="background-color: #dcdcdc;">
                                                <p style="font-size: 15px; font-weight: bold">Ver mas
                                                </p>
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    @endif
                    <div class="d-flex justify-content-between my-1">
                        @livewire('like-component', ['publication' => $publication], key('likesd' . $publication->id))
                        @if (count($publication->comments) > 0)
                            <a style="font-size:15px; color:#000000;" data-bs-toggle="collapse"
                                href="#collapse{{ $publication->id }}" role="button"
                                aria-controls="collapse{{ $publication->id }}">
                                Ver comentarios (<?= count($publication->comments) ?>)
                            </a>
                        @endif
                    </div>
                    {{-- Formulario de comentarios --}}
                    <div>

                        @livewire('comment-component', ['publication' => $publication], key('commentPub' . $publication->id))
                    </div>
                    <div class="collapse mt-2 ml-3 mb-1" id="collapse{{ $publication->id }}"
                        wire:key="pubcol-{{ $publication->id }}">
                        @foreach ($publication->comments as $comment)
                            <div class="nombre d-flex flex-row" wire:key="comment-{{ $comment->id }}">
                                <div class="com_image">
                                    <div class="card-photo rounded-circle " style="width: 35px; height:35px;">
                                        @if ($comment->user->image == null)
                                            <a style="color: inherit;"
                                                href="{{ route('profile.view', ['prof' => $comment->user_id]) }}">
                                                <p
                                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                    <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                                </p>
                                            </a>
                                        @else
                                            <a style="color: inherit;"
                                                href="{{ route('profile.view', ['prof' => $comment->user_id]) }}">
                                                <img style="width: 100%; height:100%; object-fit: cover;"
                                                    src="{{ $comment->user->image }}">
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <p class="m-0 px-2" style="font-weight: bold">
                                            {{ $comment->user->name . ' ' . $comment->user->lastname }}
                                        </p>
                                    </div>
                                    <p class="m-0 px-2">
                                        <span style="font-size: 12px">
                                            {{ $comment->created_at->diffforhumans() }}
                                        </span>
                                        <br>
                                        {{ $comment->content }}
                                    </p>
                                </div>
                                <hr>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card">
            <div class="card-body text-center">
                {{ $publications->links() }}
            </div>
        </div>
    @endif
    {{-- <script>
            document.addEventListener('livewire:load', function() {
                window.addEventListener('comment-added', event => {
                    const commentCreated = event.detail.commentCreated
                    const listComments = document.querySelector('#collapse' + commentCreated.publication_id)
                    if (!listComments.classList.contains('collapse')) {
                        listComments.classList.add('collapse');
                        listComments.classList.add('show');
                    } else {
                        if (!listComments.classList.contains('show')) {
                            listComments.classList.add('show');
                        }
                    }
                    console.log(listComments);
                    const content = document.querySelector('#collapse' + commentCreated.publication_id).innerHTML;
                    var commentHTML = `<div class="nombre d-flex flex-row">
                                <div class="com_image">
                                    <div class="card-photo rounded-circle " style="width: 35px; height:35px;">
                                        @if (auth()->user()->image == null)
                                            <a style="color: inherit;"
                                                href="{{ route('profile.view', ['prof' =>  auth()->user()->id]) }}">
                                                <p
                                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                    <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                                </p>
                                            </a>
                                        @else
                                            <a style="color: inherit;"
                                                href="{{ route('profile.view', ['prof' => auth()->user()->id]) }}">
                                                <img style="width: 100%; height:100%; object-fit: cover;"
                                                    src="{{ auth()->user()->image }}">
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <p class="m-0 px-2" style="font-weight: bold">
                                            {{ auth()->user()->name.' '. auth()->user()->lastname }}
                                        </p>
                                    </div>
                                    <p class="m-0 px-2">
                                        <span style="font-size: 12px">
                                            {{ now()->diffforhumans() }}
                                        </span>
                                        <br>
                                        ` + commentCreated.content + `
                                    </p>
                                </div>
                                <hr>
                            </div>`;
                    listComments.innerHTML = commentHTML + content

                })
            })
            document.addEventListener('DOMContentLoaded', () => {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            })
        </script> --}}
</div>
