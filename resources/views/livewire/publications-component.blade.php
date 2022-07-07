<div>
    @if (count($publications) <= 0)
        <p>No hay Publicaciones</p>
    @else
        @foreach ($publications as $publication)
            <div class="card mb-4 shadow-sm" style="border-radius: 20px" wire:key="pub-{{ $publication->id }}">
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
    <script>
        document.addEventListener('livewire:load', function() {
            window.addEventListener('comment-added', event => {
                const commentCreated =  event.detail.commentCreated
                const listComments = document.querySelector('#collapse' + commentCreated.publication_id)
                if(!listComments.classList.contains('collapse')){
                    listComments.classList.add('collapse');
                    listComments.classList.add('show');
                }else{
                    if(!listComments.classList.contains('show')){
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
                                        `+commentCreated.content+`
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
    </script>
</div>
