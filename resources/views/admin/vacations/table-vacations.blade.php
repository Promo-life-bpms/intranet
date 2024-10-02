<div>
    <style>
        table .form-control {
            padding: 2px !important;
        }

        table td {
            padding: 2px !important;
        }
    </style>

    <div class="row mb-3">
        <div class="col-6">
            <h3>Directorio de vacaciones</h3>
        </div>
        <div class="col-4" style="text-align: end">
            <input wire:model='keyWord' type="text" class="form-control" name="search" id="search"
                placeholder="Buscar Empleados">
        </div>
        <div class="col-2" style="text-align: end">
            <a style="margin-left: 20px;" href=" {{ route('admin.vacations.export') }} " type="button"
                class="btn btn btn-success">Exportar Excel</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped" id="table-directory">
            <thead style="background-color: #072A3B; color: white;">
                <tr class="text-center">
                    <th>Nombre</th>
                    <th>Ingreso</th>
                    <th>Dias Totales</th>
                    <th>Dias Disfrutados</th>
                    <th>Dias Disponibles</th>
                    {{-- <th>Respetar Vacaciones Vencidas</th> --}}
                    <th>Ver Detalle</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    @if ($user->status == 1)
                        @if (!$user->hasRole('becario'))
                            <tr class="text-center">
                                <td>
                                    {{ $user->name }} {{ $user->lastname }}
                                </td>
                                <td>
                                    {{ $user->employee->date_admission->format('d/m/Y') }}
                                </td>
                                <td>
                                    {{ $user->vacationsComplete()->sum('days_availables') }}</b>
                                </td>
                                <td>
                                    {{ $user->vacationsComplete()->sum('days_enjoyed') }}</b>
                                </td>
                                <td>
                                    {{ $user->employee->take_expired_vacation ? $user->vacationsComplete()->sum('dv') : $user->vacationsAvailables()->sum('dv') }}</b>
                                </td>
                                {{-- <td>
                                    <div>
                                        <input {{ $user->employee->take_expired_vacation ? 'checked' : '' }}
                                            type="checkbox" class="form-check-input" id="exampleCheck1"
                                            wire:click="changeStatusVacations({{ $user->id }})">
                                    </div>
                                </td> --}}
                                <td>
                                    <button type="button" class="btn btn-link" data-bs-toggle="modal"
                                        data-bs-target="#modalDetails{{ $user->id }}">
                                        Ver Detalle
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
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <div class="d-flex">
                                                            <strong class="mr-1">Empresa: </strong>
                                                            {{ $user->employee->companies[0]->name_company }}
                                                        </div>

                                                        <div class="d-flex">
                                                            <strong class="mr-1">Alta: </strong>
                                                            {{ $user->employee->date_admission->format('d/m/Y') }}
                                                        </div>

                                                        <div class="d-flex">
                                                            <strong class="mr-1">Calculo al: </strong>
                                                            {{ now()->format('d/m/Y') }}
                                                            @php
                                                                $yearsWork = $user->employee->date_admission->diffInYears(
                                                                    now(),
                                                                );
                                                                $lastPeriodYear =
                                                                    (string) ((int) $user->employee->date_admission->format(
                                                                        'Y',
                                                                    ) + $yearsWork);
                                                                $lastPeriodCurrent = \Carbon\Carbon::parse(
                                                                    $lastPeriodYear .
                                                                        '-' .
                                                                        (string) $user->employee->date_admission->format(
                                                                            'm-d',
                                                                        ),
                                                                );
                                                                $daysItsYear = $lastPeriodCurrent->diffInDays(now());
                                                            @endphp
                                                        </div>

                                                        <div class="d-flex">
                                                            <strong class="mr-1">Dias transcurridos del
                                                                {{ $lastPeriodCurrent->format('d/m/Y') . ' al ' . now()->format('d/m/Y') }}:
                                                            </strong>
                                                            {{ $daysItsYear . ' dias' }}
                                                        </div>
                                                    </div>
                                                    <div style="overflow: auto; max-height: 500px">
                                                        <table class="table table-bordered table-hover">
                                                            <thead style="background-color: #072A3B; color: white;">
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col" class="text-center">Año</th>
                                                                    <th scope="col" class="text-center">Periodo</th>

                                                                    <th scope="col" class="text-center">
                                                                        Correspondientes</th>
                                                                    <th scope="col" class="text-center">Disfrutados
                                                                    </th>
                                                                    <th scope="col" class="text-center">Restantes
                                                                    </th>


                                                                    <th scope="col" class="text-center">Vencimiento
                                                                    </th>
                                                                    {{-- <th scope="col" class="text-center">Editar</th> --}}
                                                                    <th scope="col" class="text-center">Estado</th>
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
                                                                        $date_start = new \Carbon\Carbon(
                                                                            $vacation->date_start,
                                                                        );
                                                                        $date_end = new \Carbon\Carbon(
                                                                            $vacation->date_end,
                                                                        );
                                                                        $cutoff_date = new \Carbon\Carbon(
                                                                            $vacation->cutoff_date,
                                                                        );
                                                                        $totalCalculados =
                                                                            round($totalCalculados) +
                                                                            round($vacation->days_availables);

                                                                        $totalDisponibles =
                                                                            round($totalDisponibles) +
                                                                            round($vacation->dv);

                                                                        $totalDisfrutados =
                                                                            round($totalDisfrutados) +
                                                                            round($vacation->days_enjoyed);
                                                                    @endphp
                                                                    <tr>
                                                                        <td scope="row">{{ $loop->iteration }}</td>
                                                                        <td class="text-center">
                                                                            {{ $date_start->format('Y') }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $date_start->format('d-m-Y') }}
                                                                            al
                                                                            {{ $date_end->format('d-m-Y') }}</td>
                                                                        <td class="text-center">
                                                                            {{ round($vacation->days_availables) }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <p
                                                                                class="{{ $vacation->days_enjoyed <= $vacation->days_availables ? '' : 'text-danger' }} m-0 font-bold">
                                                                                {{ $vacation->days_enjoyed }}
                                                                            </p>
                                                                        </td>
                                                                        {{-- <td class="text-center">{{ $vacation->dv }}</td> --}}
                                                                        <td class="text-center">
                                                                            {{ $vacation->dv }}</td>

                                                                        <td class="text-center">
                                                                            {{ $cutoff_date->format('d-m-Y') }}
                                                                        </td>
                                                                        {{-- <td style="width: 150px" class="text-center">
                                                                            <div class="d-flex">
                                                                                <input type="number"
                                                                                    wire:model="daysEnjoyed.{{ $user->id }}.{{ $vacation->id }}"
                                                                                    wire:keydown.enter="updateDays({{ $vacation->id }}, {{ $user->id }}, {{ $vacation->id }})"
                                                                                    class="form-control text-center"
                                                                                    placeholder="Dias disfrutados">
                                                                            </div>
                                                                        </td> --}}
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
                                                                    <td></td>
                                                                    {{-- <td></td> --}}
                                                                    <td class="text-center">
                                                                        {{ $totalCalculados }}
                                                                        <br>
                                                                        Dias Cumplidos
                                                                    </td>
                                                                    <td class="text-center">{{ $totalDisfrutados }}
                                                                        <br> Dias Disfrutados
                                                                    </td>
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

<style>
    /*Estilos de paginacion*/
    .pagination {
        display: flex;
        justify-content: end;

    }

    .page-item .page-link {
        font-size: .875rem;
        border-color: transparent;
    }

    .page-item.active .page-link {
        background-color: #435ebe;
        border-color: #435ebe;
        color: #fff;
        z-index: 3;
        border-radius: 27px;
    }

    .page-item.disabled .page-link {
        background-color: #fff;
        color: #6c757d;
        pointer-events: none;
        border-color: transparent;
    }
</style>
