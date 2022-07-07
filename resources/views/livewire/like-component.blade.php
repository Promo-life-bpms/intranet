<div id="boton" class="p-0 flex-grow-1">
    @php
        $users = '';
        foreach ($publication->like as $user) {
            $users = $users . $user->name . ' ' . $user->lastname . '<br>';
        }
    @endphp
    <div class="like-container p-0" style="margin-top: -24px; overflow:hidden;">
        <span class="like-btn {{ auth()->user()->meGusta->contains($publication->id)? 'like-active': '' }}"
            wire:click="like({{ $publication->id }})" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-html="true" title="{{ $users }}">
        </span>
        <span class="badge bg-danger notification-icon">{{ $publication->like->count() }}</span>
    </div>
</div>
