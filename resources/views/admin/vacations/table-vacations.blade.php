<div>
    <div class="d-flex justify-content-end pb-3">
        <input wire:model='keyWord' type="text" class="form-control" name="search" id="search"
            placeholder="Buscar Empleados">
    </div>

    <div class="d-flex justify-content-between">
        <div>
            <p>Resultados de Busqueda</p>
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
        <div wire:loading.flex>
            <div class="spinner-border text-info" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped" id="table-directory">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Fecha de Ingreso</th>
                    <th>Periodo Actual</th>
                    <th>Periodo Anterior</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }} <br>{{ $user->lastname }}</td>
                        <td>{{ Str::limit($user->employee->date_admission, 10) }}</td>
                        @php
                            $diasDisponibles = 0;
                        @endphp
                        @foreach ($user->vacationsAvailables()->where('period', '<>', 3)->orderBy('period', 'ASC')->get()
    as $vacation)
                            <td>
                                @php
                                    $randomKey = time();
                                    $diasDisponibles = $diasDisponibles + $vacation->dv;
                                @endphp
                                <div>
                                    <strong>Periodo:</strong> {{ $vacation->period == 1 ? 'Actual' : 'Vencido' }}
                                    <br>
                                    <strong>Expiracion:</strong> {{ $vacation->cutoff_date }}
                                    <br>
                                    <strong>Calculados:</strong> {{ $vacation->days_availables }}
                                    <br>
                                    <strong>Disponibles:</strong> {{ $vacation->dv }}
                                    <br>
                                    <strong>Disfrutados:</strong> {{ $vacation->days_enjoyed }}
                                    <!-- Button trigger modal -->
                                    <div class="d-flex">
                                        <input type="number"
                                            wire:model="daysEnjoyed.{{ $user->id }}.{{ $vacation->period }}"
                                            class="form-control" placeholder="Colocar dias disfrutados">
                                        <button class="btn btn-warning d-flex"
                                            wire:click="updateDays({{ $vacation->id }}, {{ $user->id }}, {{ $vacation->period }})">Actualizar
                                        </button>
                                    </div>
                                </div>
                                {{-- @livewire('vacations.update-days-enjoyed', ['data' => $vacation], key($user->id)) --}}
                            </td>
                        @endforeach
                        @if (count($user->vacationsAvailables) == 1)
                            <td>
                                No hay informacion del periodo anterior
                            </td>
                        @endif
                        <td class="">
                            <p>{{ $diasDisponibles }} dias disponibles</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
