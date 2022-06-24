@extends('layouts.app')

@section('template_title')
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Catalogo de Proveedores') }}
                            </span>

                            <div class="float-right">
                                @role('admin')
                                    <a href="{{ route('providers.create') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Agregar Nuevo') }}
                                    </a>
                                    <a href="{{ route('providers.createImport') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Importar Proveedores') }}
                                    </a>
                                @endrole
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tableProviders">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

                                        <th>Nombre</th>
                                        <th>Palabra clave</th>
                                        <th>Producto o Servicio</th>
                                        <th>Contacto</th>
                                        <th>Position</th>
                                        <th>Telefono de Oficina </th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($providers as $provider)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $provider->name }}</td>
                                            <td>{{ $provider->service }}</td>
                                            <td>{{ $provider->type }}</td>
                                            <td>{{ $provider->name_contact }}</td>
                                            <td>{{ $provider->position }}</td>
                                            <td>{{ $provider->tel_office }}</td>
                                            <td>
                                                <form action="{{ route('providers.destroy', $provider->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('providers.show', $provider->id) }}"><i
                                                            class="fa fa-fw fa-eye"></i> Ver Informacion</a>
                                                    @role('admin')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('providers.edit', $provider->id) }}"><i
                                                                class="fa fa-fw fa-edit"></i> Edit</a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fa fa-fw fa-trash"></i> Delete</button>
                                                    @endrole
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $providers->links() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <script>
        let jquery_datatable = $("#tableProviders").DataTable()
    </script>
@endsection
