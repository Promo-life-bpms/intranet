@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Gestión de dispositivos</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-directory">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col" class="text-center">Departamento</th>
                        <th scope="col" class="text-center">Puesto</th>
                        <th scope="col">Equipo y dispositivos</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users_data as $user)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td><b>{{ $user->fullname}}</td>
                            <td class="text-center">{{ $user->department }}</td>
                            <td class="text-center">{{ $user->position }}</td>
                            <td class="text-center">{{-- 
                                @if (count($user->devices)==0 )
                                    0
                                @else
                                    1
                                @endif
                                --}}
                        </td>
                            <td>
                                <div class="d-flex">

                                    <!-- <div>
                                        <a style="" href="{{ route('admin.users.edit', ['user' => $user->id]) }}"
                                            type="button" class="btn btn-warning">Editar</a>
                                    </div>
                                    <div>
                                        <form class="form-delete"
                                            action="{{ route('admin.users.destroy', ['user' => $user->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button style="" type="submit" class="btn btn-danger">Borrar</button>
                                        </form>
                                    </div> -->
                                </div>
                                <div class="w-100">
                                    <!-- <form class="form-reset"
                                        action="{{ route('admin.user.sendAccessUnit', ['user' => $user->id]) }}"
                                        method="GET">
                                        @csrf
                                        <button style="" type="submit"
                                            class="btn btn-primary btn-block mt-1">Acceso</button>
                                    </form> -->
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
