
<div>
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Generar reportes</h3>

            <div class="d-flex justify-content-end">

                <input type="date" class="form-control mr-3" wire:model="start" wire:change="buscar">
                <input type="date" class="form-control mr-3" wire:model="end" wire:change="buscar">

                {!! Form::open(['route' => 'request.export']) !!}
                <button type="submit" class="btn btn-success" style="margin-left: 20px;">Exportar</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-directory">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Solicitante</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Pago</th>
                        <th scope="col">Fechas ausencia</th>
                        <th scope="col">Tiempo</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Creado el</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}</td>
                            <td>{{ $request->type_request }}</td>
                            <td>{{ $request->payment }}</td>
                            <td>
                                @foreach ($request->requestdays as $day)
                                    @php
                                        $dayFormater = \Carbon\Carbon::parse($day->start);
                                    @endphp
                                    {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}
                                @endforeach
                            </td>

                            <td>
                                @if ($request->start == null)
                                    Tiempo completo
                                @else
                                    @if ($request->end == null)
                                        {{ 'Salida: ' . $request->start . ' ' }}
                                    @else
                                        {{ 'Salida: ' . $request->start . ' ' . 'Reingreso:' . ' ' . $request->end }}
                                    @endif
                                @endif
                            </td>
                            <td>{{ $request->reason }}</td>
                            <td>{{ $request->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $requests->links() }}
        </div>

    </div>
    {{-- <div class="modal fade" id="modalBusqueda" tabindex="-1" aria-labelledby="modalBusquedaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBusquedaLabel">Realizar Búsqueda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'request.filter']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            {!! Form::label('start', 'Fecha de inicio') !!}
                            {!! Form::date('start', null, ['class' => 'form-control', 'placeholder' => 'Ingresa la fecha de vencimiento']) !!}
                            @error('start')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror
                        </div>
                        <div class="col">
                            {!! Form::label('end', 'Fecha de fin') !!}
                            {!! Form::date('end', null, ['class' => 'form-control', 'placeholder' => 'Ingresa la fecha de vencimiento']) !!}
                            @error('fin')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {!! Form::submit('Buscar', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalBusquedaDia" tabindex="-1" aria-labelledby="modalBusquedaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBusquedaLabel">Realizar Búsqueda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'request.filter.data']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            {!! Form::label('start', 'Fecha de inicio') !!}
                            {!! Form::date('start', null, ['class' => 'form-control', 'placeholder' => 'Ingresa la fecha de vencimiento']) !!}
                            @error('start')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror
                        </div>
                        <div class="col">
                            {!! Form::label('end', 'Fecha de fin') !!}
                            {!! Form::date('end', null, ['class' => 'form-control', 'placeholder' => 'Ingresa la fecha de vencimiento']) !!}
                            @error('fin')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {!! Form::submit('Buscar', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div> --}}
</div>
