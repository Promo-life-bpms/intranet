<table>
    <thead>
        <tr>
            <th >#</th>
            <th >Nombre</th>
            <th >Apellidos</th>
            <th >Departamento</th>
            <th >Numero</th>
            <th >Promolife</th>
            <th >BH-Trademarket</th>
            <th >Trademarket</th>
            <th >PormoDreams</th>
        </tr>
    </thead>

    <tbody>
    
        @foreach ($userContact as $contact)
        @if ($contact->user->employee->position->department->name == 'Direccion')
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $contact->user->name }}</td>
            <td>{{ $contact->user->lastname }}</td>
            <td>{{ $contact->user->employee->position->department->name }}</td>
            <td>{{ $contact->num_tel }}</td>
            <td>{{ $contact->correo1 }}</td>
            <td>{{ $contact->correo2 }}</td>
            <td>{{ $contact->correo3 }}</td>
            <td>{{ $contact->correo4 }}</td>
        </tr>

        @elseif ($contact->user->employee->position->department->name == 'Recursos Humanos')
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $contact->user->name }}</td>
                <td>{{ $contact->user->lastname }}</td>
                <td>{{ $contact->user->employee->position->department->name }}</td>
                <td>{{ $contact->num_tel }}</td>
                <td>{{ $contact->correo1 }}</td>
                <td>{{ $contact->correo2 }}</td>
                <td>{{ $contact->correo3 }}</td>
                <td>{{ $contact->correo4 }}</td>
            </tr>

            @elseif ($contact->user->employee->position->department->name == 'Administracion')
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $contact->user->name }}</td>
                <td>{{ $contact->user->lastname }}</td>
                <td>{{ $contact->user->employee->position->department->name }}</td>
                <td>{{ $contact->num_tel }}</td>
                <td>{{ $contact->correo1 }}</td>
                <td>{{ $contact->correo2 }}</td>
                <td>{{ $contact->correo3 }}</td>
                <td>{{ $contact->correo4 }}</td>
            </tr>

            @elseif ($contact->user->employee->position->department->name == 'Ventas BH')
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $contact->user->name }}</td>
                <td>{{ $contact->user->lastname }}</td>
                <td>{{ $contact->user->employee->position->department->name }}</td>
                <td>{{ $contact->num_tel }}</td>
                <td>{{ $contact->correo1 }}</td>
                <td>{{ $contact->correo2 }}</td>
                <td>{{ $contact->correo3 }}</td>
                <td>{{ $contact->correo4 }}</td>
            </tr>

            @elseif ($contact->user->employee->position->department->name == 'Ventas PL')
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $contact->user->name }}</td>
                <td>{{ $contact->user->lastname }}</td>
                <td>{{ $contact->user->employee->position->department->name }}</td>
                <td>{{ $contact->num_tel }}</td>
                <td>{{ $contact->correo1 }}</td>
                <td>{{ $contact->correo2 }}</td>
                <td>{{ $contact->correo3 }}</td>
                <td>{{ $contact->correo4 }}</td>
            </tr>
        
            @elseif ($contact->user->employee->position->department->name == 'Importaciones')
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->user->name }}</td>
                    <td>{{ $contact->user->lastname }}</td>
                    <td>{{ $contact->user->employee->position->department->name }}</td>
                    <td>{{ $contact->num_tel }}</td>
                    <td>{{ $contact->correo1 }}</td>
                    <td>{{ $contact->correo2 }}</td>
                    <td>{{ $contact->correo3 }}</td>
                    <td>{{ $contact->correo4 }}</td>
                </tr>

            @elseif ($contact->user->employee->position->department->name == 'Diseno')
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->user->name }}</td>
                    <td>{{ $contact->user->lastname }}</td>
                    <td>{{ $contact->user->employee->position->department->name }}</td>
                    <td>{{ $contact->num_tel }}</td>
                    <td>{{ $contact->correo1 }}</td>
                    <td>{{ $contact->correo2 }}</td>
                    <td>{{ $contact->correo3 }}</td>
                    <td>{{ $contact->correo4 }}</td>
                </tr>
            @elseif ($contact->user->employee->position->department->name == 'Sistemas')
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->user->name }}</td>
                    <td>{{ $contact->user->lastname }}</td>
                    <td>{{ $contact->user->employee->position->department->name }}</td>
                    <td>{{ $contact->num_tel }}</td>
                    <td>{{ $contact->correo1 }}</td>
                    <td>{{ $contact->correo2 }}</td>
                    <td>{{ $contact->correo3 }}</td>
                    <td>{{ $contact->correo4 }}</td>
            </tr>

            @elseif ($contact->user->employee->position->department->name == 'Operaciones')
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->user->name }}</td>
                    <td>{{ $contact->user->lastname }}</td>
                    <td>{{ $contact->user->employee->position->department->name }}</td>
                    <td>{{ $contact->num_tel }}</td>
                    <td>{{ $contact->correo1 }}</td>
                    <td>{{ $contact->correo2 }}</td>
                    <td>{{ $contact->correo3 }}</td>
                    <td>{{ $contact->correo4 }}</td>
                </tr>

            @elseif ($contact->user->employee->position->department->name == 'Tecnologia e Innovacion')
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->user->name }}</td>
                    <td>{{ $contact->user->lastname }}</td>
                    <td>{{ $contact->user->employee->position->department->name }}</td>
                    <td>{{ $contact->num_tel }}</td>
                    <td>{{ $contact->correo1 }}</td>
                    <td>{{ $contact->correo2 }}</td>
                    <td>{{ $contact->correo3 }}</td>
                    <td>{{ $contact->correo4 }}</td>
                </tr>


            @elseif ($contact->user->employee->position->department->name == 'E-commerce')
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->user->name }}</td>
                    <td>{{ $contact->user->lastname }}</td>
                    <td>{{ $contact->user->employee->position->department->name }}</td>
                    <td>{{ $contact->num_tel }}</td>
                    <td>{{ $contact->correo1 }}</td>
                    <td>{{ $contact->correo2 }}</td>
                    <td>{{ $contact->correo3 }}</td>
                    <td>{{ $contact->correo4 }}</td>
                </tr>

            @elseif ($contact->user->employee->position->department->name == 'Cancun')
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->user->name }}</td>
                    <td>{{ $contact->user->lastname }}</td>
                    <td>{{ $contact->user->employee->position->department->name }}</td>
                    <td>{{ $contact->num_tel }}</td>
                    <td>{{ $contact->correo1 }}</td>
                    <td>{{ $contact->correo2 }}</td>
                    <td>{{ $contact->correo3 }}</td>
                    <td>{{ $contact->correo4 }}</td>
                </tr>
            @else
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contact->user->name }}</td>
                    <td>{{ $contact->user->lastname }}</td>
                    <td>{{ $contact->user->employee->position->department->name }}</td>
                    <td>{{ $contact->num_tel }}</td>
                    <td>{{ $contact->correo1 }}</td>
                    <td>{{ $contact->correo2 }}</td>
                    <td>{{ $contact->correo3 }}</td>
                    <td>{{ $contact->correo4 }}</td>
                </tr>
        @endif
    @endforeach

    </tbody>
</table>