<div>
    <style>
        table .form-control {
            padding: 2px !important;
        }

        table td {
            padding: 2px !important;
        }
    </style>
    <div class="d-flex justify-content-end pb-3">
        <input wire:model='keyWord' type="text" class="form-control" name="search" id="search"
            placeholder="Buscar Empleados">
    </div>
    <div class="table-responsive">
        <table class="table table-striped" id="table-directory">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Ingreso</th>
                    <th>Dias Disponibles</th>
                    <th>Ver Detalle</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    @if ($user->status == 1)
                        @if (!$user->hasRole('becario'))
                            <tr>
                                <td>
                                    {{ $user->name }} {{ $user->lastname }}
                                </td>
                                <td>
                                    {{ $user->employee->date_admission->format('d/m/Y') }}
                                </td>
                                <td>
                                    {{ $user->vacationsAvailables()->where('period', '<>', 3)->sum('dv') }}</b>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalDetails{{ $user->id }}">
                                        Ver Detalles
                                    </button>
                                    <!-- Modal -->
                                    <div wire:ignore.self class="modal fade" id="modalDetails{{ $user->id }}"
                                        tabindex="-1" aria-labelledby="modalDetails{{ $user->id }}Label"
                                        aria-hidden="true">
                                        <div class="modal-dialog" style="max-width: 1300px;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalDetails{{ $user->id }}Label">
                                                        {{ $user->employee->user->name . ' ' . $user->employee->user->lastname }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-left">
                                                    <div>
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <p class="m-0"><strong>Empresa:</strong>
                                                                    {{ $user->employee->companies[0]->name_company }}
                                                                </p>
                                                                <p class="m-0"><strong>Alta:</strong>
                                                                    {{ $user->employee->date_admission->format('d-M-Y') }}
                                                                </p>
                                                                <p class="m-0"><strong>Calculo al: </strong>
                                                                    {{ now()->format('d-M-Y') }}
                                                                </p>
                                                                @php
                                                                    $yearsWork = $user->employee->date_admission->diffInYears(now());
                                                                    $lastPeriodYear = (string) ((int) $user->employee->date_admission->format('Y') + $yearsWork);
                                                                    $lastPeriodCurrent = \Carbon\Carbon::parse($lastPeriodYear . '-' . (string) $user->employee->date_admission->format('m-d'));
                                                                    $daysItsYear = $lastPeriodCurrent->diffInDays(now());
                                                                @endphp
                                                                <p class="m-0"><strong>Dias transcurridos del
                                                                        {{ $lastPeriodCurrent->format('d-M-Y') . ' al ' . now()->format('d-M-Y') }}:
                                                                    </strong> {{ $daysItsYear . ' dias' }}
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <div class="d-flex justify-content-between">
                                                                    <div wire:loading.flex>
                                                                        <div class="spinner-border text-info"
                                                                            role="status">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                    </div>
                                                                    @if (session()->has('error'))
                                                                        <div class="alert alert-danger" wire:poll.3s>
                                                                            {{ session('error') }}
                                                                        </div>
                                                                    @endif
                                                                    @if (session()->has('message'))
                                                                        <div class="alert alert-success p-1 m-0"
                                                                            wire:poll.3s>
                                                                            {{ session('message') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="m-0"><strong>Dias irregulares en periodos
                                                                    expirados:</strong>
                                                                @if ($user->vacationsComplete()->where('period', '=', 3)->sum('dv') < 0)
                                                                    {{ $user->vacationsComplete()->where('period', '=', 3)->sum('dv') }}
                                                                    dias excedidos y seran descontados de los periodos
                                                                    vigentes.
                                                                @elseif ($user->vacationsComplete()->where('period', '=', 3)->sum('dv') > 0)
                                                                    {{ $user->vacationsComplete()->where('period', '=', 3)->sum('dv') }}
                                                                    dias expirados que no seran contemplados en los
                                                                    periodos vigentes.
                                                                @else
                                                                    {{ $user->vacationsComplete()->where('period', '=', 3)->sum('dv') }}.
                                                                    No tiene dias expirados o vacaciones tomadas en
                                                                    exceso.
                                                                @endif
                                                            </p class="m-0">
                                                            <p class="m-0"><strong>Dias Actuales:</strong>
                                                                {{ $user->vacationsComplete()->where('period', '=', 1)->sum('days_availables') }}
                                                            </p>
                                                            <p class="m-0"><strong>Dias Totales:</strong>
                                                                {{ $user->vacationsAvailables()->sum('dv') +
                                                                    ($user->vacationsComplete()->where('period', '=', 3)->sum('dv') < 0
                                                                        ? $user->vacationsComplete()->where('period', '=', 3)->sum('dv')
                                                                        : 0) }}
                                                            </p>
                                                        </div>
                                                        <div>

                                                        </div>
                                                    </div>
                                                    <div style="overflow: auto; max-height: 500px">
                                                        <table class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col" class="text-center">Año</th>
                                                                    <th scope="col" class="text-center">Disfrutados
                                                                    </th>
                                                                    {{-- <th scope="col" class="text-center">Disponibles
                                                                    </th> --}}
                                                                    <th scope="col" class="text-center"
                                                                        colspan="2">Periodos de
                                                                        Vacaciones</th>
                                                                    <th scope="col" class="text-center">Vencimiento
                                                                    </th>
                                                                    <th scope="col" class="text-center">Editar</th>
                                                                    <th scope="col" class="text-center">Periodo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $totalCalculados = 0;
                                                                    $totalDisponibles = 0;
                                                                    $totalDisfrutados = 0;
                                                                @endphp
                                                                @foreach ($user->vacationsComplete()->orderBy('date_end', 'DESC')->get() as $vacation)
                                                                    @php
                                                                        $date_start = new \Carbon\Carbon($vacation->date_start);
                                                                        $date_end = new \Carbon\Carbon($vacation->date_end);
                                                                        $cutoff_date = new \Carbon\Carbon($vacation->cutoff_date);
                                                                        $totalCalculados = $totalCalculados + $vacation->days_availables;
                                                                        $totalDisponibles = $totalDisponibles + $vacation->dv;
                                                                        $totalDisfrutados = $totalDisfrutados + $vacation->days_enjoyed;
                                                                    @endphp
                                                                    <tr>
                                                                        <td scope="row">{{ $loop->iteration }}</td>
                                                                        <td class="text-center">
                                                                            {{ $date_start->format('Y') }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <p
                                                                                class="{{ $vacation->days_enjoyed <= $vacation->days_availables ? 'text-success' : 'text-danger' }} m-0 font-bold">
                                                                                {{ $vacation->days_enjoyed }}
                                                                            </p>
                                                                        </td>
                                                                        {{-- <td class="text-center">{{ $vacation->dv }}</td> --}}
                                                                        <td class="text-center">
                                                                            {{ $vacation->dv }} |
                                                                            {{ $vacation->days_availables }}</td>
                                                                        <td class="text-center">
                                                                            {{ $date_start->format('d/m/Y') }}
                                                                            al
                                                                            {{ $date_end->format('d/m/Y') }}
                                                                        <td class="text-center">
                                                                            {{ $cutoff_date->format('d/m/Y') }}
                                                                        </td>
                                                                        <td style="width: 150px" class="text-center">
                                                                            <div class="d-flex">
                                                                                <input type="number"
                                                                                    wire:model="daysEnjoyed.{{ $user->id }}.{{ $vacation->id }}"
                                                                                    wire:keydown.enter="updateDays({{ $vacation->id }}, {{ $user->id }}, {{ $vacation->id }})"
                                                                                    class="form-control text-center"
                                                                                    placeholder="Dias disfrutados">
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            @switch($vacation->period)
                                                                                @case(1)
                                                                                    <span class="badge bg-success">
                                                                                        Actual
                                                                                    </span>
                                                                                @break

                                                                                @case(2)
                                                                                    <span class="badge bg-warning">
                                                                                        Anterior
                                                                                    </span>
                                                                                @break

                                                                                @case(3)
                                                                                    <span class="badge bg-danger">
                                                                                        Expirado
                                                                                    </span>
                                                                                @break

                                                                                @default
                                                                            @endswitch
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-center">{{ $totalDisfrutados }}
                                                                        <br> Dias Tomados
                                                                    </td>
                                                                    {{-- <td></td> --}}
                                                                    <td class="text-center">{{ $totalCalculados }} <br>
                                                                        Dias Cumplidos</td>
                                                                    <td class="text-center">
                                                                        {{ $totalCalculados - $totalDisfrutados }} <br>
                                                                        Dias Restantes</td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
