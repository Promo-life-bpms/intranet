<div>
    <div class="d-flex justify-content-end pb-3">
        <input wire:model='keyWord' type="text" class="form-control" name="search" id="search"
            placeholder="Buscar Empleados">
    </div>

    <div class="d-flex justify-content-between">
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
                    <th>Nombre</th>
                    <th>Periodo Actual</th>
                    <th>Periodo Anterior</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    @if ($user->status == 1)
                        @if (!$user->hasRole('becario'))
                            <tr>
                                <td>{{ $user->name }} {{ $user->lastname }} <br>
                                    Ingreso: {{ $user->employee->date_admission->format('d-m-Y') }} <br>
                                    Dias disponibles de ambos periodos:
                                    <b>{{ $user->vacationsAvailables()->where('period', '<>', 3)->sum('dv') }}</b>
                                </td>
                                @foreach ($user->vacationsAvailables()->where('period', '<>', 3)->orderBy('period', 'ASC')->get() as $vacation)
                                    <td>
                                        @php
                                            $dateCut = new \Carbon\Carbon($vacation->cutoff_date);
                                        @endphp
                                        <div>
                                            <strong>Periodo:</strong>
                                            {{ $dateCut->subYears(2)->format('d-m-Y') . ' - ' . $dateCut->addYear(1)->format('d-m-Y') }}
                                            <br>
                                            <strong>Vencimiento:</strong> {{ $dateCut->addYear(1)->format('d-m-Y') }}
                                            <br>
                                            <strong>Dias
                                                {{ $vacation->period == 1 ? 'Actuales Calculados' : 'Vencidos Calculados' }}:</strong>
                                            {{ $vacation->days_availables }}
                                            <br>
                                            {{-- <strong>Dias Disponibles
                                                {{ $vacation->period == 1 ? 'del Periodo Actual: ' : 'del Periodo Vencido: ' }}</strong>
                                            {{ $vacation->dv }}
                                            <br> --}}
                                            <strong>Dias Disfrutados
                                                {{ $vacation->period == 1 ? 'del Periodo Actual: ' : 'del Periodo Vencido: ' }}</strong>
                                            {{ $vacation->days_enjoyed }}
                                            <!-- Button trigger modal -->
                                            @role('admin')
                                                <div class="d-flex">
                                                    <input type="number"
                                                        wire:model="daysEnjoyed.{{ $user->id }}.{{ $vacation->period }}"
                                                        class="form-control" placeholder="Colocar dias disfrutados">
                                                    <button class="btn btn-warning d-flex"
                                                        wire:click="updateDays({{ $vacation->id }}, {{ $user->id }}, {{ $vacation->period }})">Actualizar
                                                    </button>
                                                </div>
                                            @endrole()
                                        </div>
                                        @php
                                            $dateCut = 0;
                                        @endphp
                                    </td>
                                @endforeach
                                @if (count($user->vacationsAvailables) == 1)
                                    <td>
                                        No hay informacion del periodo anterior
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
