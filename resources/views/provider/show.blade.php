@extends('layouts.app')

@section('template_title')
    {{ $provider->name ?? 'Show Provider' }}
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">

                            <div class="">
                                <span class="card-title">Informacion de  {{ $provider->name }}</span>
                            </div>
                            <div class="">
                                <a class="btn btn-primary" href="{{ route('providers.index') }}"> Atras</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $provider->name }}
                        </div>
                        <div class="form-group">
                            <strong>Palabra clave de servicio:</strong>
                            {{ $provider->service }}
                        </div>
                        <div class="form-group">
                            <strong>Tipo de producto o servicio:</strong>
                            {{ $provider->type }}
                        </div>
                        <div class="form-group">
                            <strong>Nombre del contacto:</strong>
                            {{ $provider->name_contact }}
                        </div>
                        <div class="form-group">
                            <strong>Puesto:</strong>
                            {{ $provider->position }}
                        </div>
                        <div class="form-group">
                            <strong>Telefono de Oficina:</strong>
                            {{ $provider->tel_office }}
                        </div>
                        <div class="form-group">
                            <strong>Telefono celular:</strong>
                            {{ $provider->tel_cel }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $provider->email }}
                        </div>
                        <div class="form-group">
                            <strong>Direccion:</strong>
                            {{ $provider->address }}
                        </div>
                        <div class="form-group">
                            <strong>Pagina web:</strong>
                            {{ $provider->web_page }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
