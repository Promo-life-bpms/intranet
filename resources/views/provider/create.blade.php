@extends('layouts.app')

@section('template_title')
    Crear Proveedor
@endsection

@section('content')
    <section class=" container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Crear Proveedor</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('providers.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('provider.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
