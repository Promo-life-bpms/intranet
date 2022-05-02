<div>
    <strong>Periodo:</strong> {{ $data->period }}
    <br>
    <strong>Expiracion:</strong> {{ $data->cutoff_date }}
    <br>
    <strong>Dias calculados:</strong> {{ $data->days_availables }}
    <br>
    <strong>Dias disponibles:</strong> {{ $data->dv }}
    <br>
    <strong>Dias disfrutados:</strong> {{ $data->days_enjoyed }}
    <br>
    <div class="d-flex">
        <input type="number" wire:model="daysEnjoyed" class="form-control" placeholder="Colocar dias disfrutados">
        <button class="btn btn-warning" wire:click="updateDays({{ $data->id }})">Actualizar</button>
    </div>
    <div wire:poll.2000ms>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
</div>
