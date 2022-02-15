@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Directorio') }}
                            </span>
                            @role('sistemas')
                                <div class="float-right">
                                    <a href="{{ route('directories.create') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Create New') }}
                                    </a>
                                </div>
                            @endrole
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table-directory">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Colaborador</th>
                                        {{-- <th>Type</th>
                                        <th>Data</th>
                                        <th>Company</th> --}}

                                        <th>Datos</th>

                                        @role('sistemas')
                                            <th>Acciones</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $user->name . ' ' . $user->lastname }}</td>
                                            <td>
                                                @foreach ($user->directory as $directory)
                                                    <p class="m-0 my-1"><strong>{{ $directory->type }}
                                                            {{ $directory->companyName->name_company }}:</strong>
                                                        {{ $directory->data }}
                                                    </p>
                                                @endforeach
                                            </td>
                                            {{-- <td>{{ $user-> }}</td>
                                            <td>{{ $user->company }}</td> --}}

                                            @role('sistemas')
                                                <td>
                                                    <form action="{{ route('directories.destroy', $user->id) }}"
                                                        method="POST">
                                                        <a class="btn btn-sm btn-primary "
                                                            href="{{ route('directories.show', $user->id) }}"><i
                                                                class="fa fa-fw fa-eye"></i> Show</a>
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('directories.edit', $user->id) }}"><i
                                                                class="fa fa-fw fa-edit"></i> Edit</a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fa fa-fw fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            @endrole
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-directory').DataTable();
        });
    </script>
@endsection
