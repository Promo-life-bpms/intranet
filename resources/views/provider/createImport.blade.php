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
                        <form method="POST" action="{{ route('providers.storeImport') }}" role="form" enctype="multipart/form-data"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="box box-info padding-1">
                                <div class="box-body">
                                    <div class="form-group">
                                        {{ Form::label('nombre') }}
                                        {!! Form::file('file',  ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
                                        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="box-footer mt20">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
