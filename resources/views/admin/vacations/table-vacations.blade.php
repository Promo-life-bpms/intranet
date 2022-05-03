<div>
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
                        @foreach ($user->vacationsAvailables()->where('period','<>',3)->orderBy('period', 'ASC')->get() as $vacation)
                            <td>
                                @php
                                    $randomKey = time();
                                @endphp
                                @livewire('vacations.update-days-enjoyed', ['data'=>$vacation ], key($randomKey))
                            </td>
                        @endforeach
                        @if (count($user->vacationsAvailables) == 1)
                            <td>
                                No hay informacion del periodo anterior
                            </td>
                        @endif
                        <td class="">
                            {{-- <a style="width:100px;"
                                href="{{ route('admin.vacations.edit', ['vacation' => $vacation->id]) }}"
                                type="button" class ="btn btn-primary">Editar</a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
