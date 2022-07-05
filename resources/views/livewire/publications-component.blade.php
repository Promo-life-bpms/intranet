<div>
    @if (count($publications) <= 0)
        <p>No hay Publicaciones</p>
    @else
        @foreach ($publications as $publication)
            <div class="card mb-4 shadow-sm" style="border-radius: 20px">
                <div class="card-body">
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
                            <p class="m-0 " style="font-weight: bold">
                                {{ $publication->user->name . ' ' . $publication->user->lastname }}
                            </p>
                            <p class="m-0">
                                {{ $publication->created_at->diffForHumans() }} </p>
                        </div>
                    </div>
                    <p class="mt-4 ">
                        {{ $publication->content_publication }} </p>
                    @if (count($publication->files) > 0)
                        <div class="row" style="overflow:hidden;">
                            @if (count($publication->files) == 1)
                                @foreach ($publication->files as $item)
                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                        data-lightbox="photos.{{ $publication->id }}" style="height: 600px;">
                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                            class="rounded shadow-sm"
                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                    </a>
                                @endforeach
                            @elseif (count($publication->files) == 2)
                                @foreach ($publication->files as $item)
                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
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
                        <div id="boton" class="p-0">
                            <div class="like-container p-0" style="margin-top: -24px; overflow:hidden;">
                                <span
                                    class="like-btn {{ auth()->user()->meGusta->contains($publication->id)? 'like-active': '' }}"
                                    wire:click="like({{ $publication->id }})">
                                </span>
                                <span class="badge bg-danger notification-icon">{{ $publication->like->count() }}
                                </span>
                            </div>
                        </div>
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
                            <input
                                type="text"
                                id="comment.{{ $publication->id }}"
                                name="comment.{{ $publication->id }}"
                                wire:model="comment.{{ $publication->id }}"
                                class="form-control flex-grow-1  @error('comment.{{ $publication->id }}') is-invalid @enderror"
                                placeholder="Escribe tu comentario...">
                            <button class="boton" wire:click="comentar({{ $publication->id }})">
                                Comentar
                            </button>
                        </div>
                        @error('content')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="collapse mt-2 ml-3 mb-1" id="collapse{{ $publication->id }}">
                        @foreach ($publication->comments as $comment)
                            <div class="nombre d-flex flex-row">
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
                                <div class="com_content">
                                    <div class="d-flex">
                                        <p class="ml-1 my-0 " style="font-weight: bold">
                                            {{ $comment->user->name . ' ' . $comment->user->lastname }}
                                        </p>
                                        <p class="ml-1  my-0">
                                            {{ $comment->created_at->diffforhumans() }}
                                        </p>
                                    </div>
                                    <p class="ml-1  my-0">
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
</div>
