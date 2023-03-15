@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex flex-row" >
        <h3 style="margin-left:16px;" class="separator">Información adicional</h3>
        </div>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
    </div>
        @endif
        {!! Form::open(['route' => 'rh.createMoreInformation', 'enctype' => 'multipart/form-data']) !!}
        
            <div class="d-flex flex-row"> 
                <h6>Informacion adicional</h6>
                <p></p>
                <div id='content'>
                    <div id='icon'>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                </div>
            </div>
                <div id='icon-text'>
                    La información adicional solo es requerida previo a la generación de la documentación de alta o cuando el status sea candidato
                </div>
        </div>
        
            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('place_of_birth', 'Lugar de nacimiento') !!}
                    {!! Form::text('place_of_birth', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el lugar de nacimiento']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('cell_phone', 'Telefono celular') !!}
                    {!! Form::number('cell_phone', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numeor de telefono celular']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('card_number', 'N° Tarjeta/Cuenta') !!}
                    {!! Form::text('card_number', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de cuenta']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('birthdate', 'Fecha de nacimiento') !!}
                    {!! Form::date('birthdate', null, ['class' => 'form-control']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('home_phone', 'Telefono de casa') !!}
                    {!! Form::number('home_phone', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono de casa']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('bank_name', 'Banco') !!}
                    {!! Form::text('bank_name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero del banco']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('fathers_name', 'Nombre del padre') !!}
                    {!! Form::text('fathers_name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo del padre']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('curp', 'CURP') !!}
                    {!! Form::text('curp', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el CURP']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('infonavit_credit', 'Credito infonavit') !!}
                    {!! Form::select('infonavit_credit', ['si' => 'Si', 'no' => 'No'], null, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('mothers_name', 'Nombre de la madre') !!}
                    {!! Form::text('mothers_name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo de la madre']) !!}
                    
                </div>

                <div class="col-sm ">
                    {!! Form::label('rfc', 'RFC') !!}
                    {!! Form::text('rfc', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el RFC']) !!}
                </div>
            
                <div class="col-sm ">
                    {!! Form::label('factor_credit_number', 'N° credito factor') !!}
                    {!! Form::text('factor_credit_number', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito factor']) !!}
                </div> 
                
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('civil_status', 'Estado Civil') !!}
                    {!! Form::select('civil_status', ['soltero' => 'Soltero(a)', 'casado' => 'Casado(a)',  'divorciado' => 'Divorciado(a)', 'viudo' => 'Viudo(a)', 'conviviente' => 'Conviviente' ], null ,['class' => 'form-control', 'placeholder' => 'Ingrese la estado civil']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('civil_status', 'N° Afiliacion IMSS') !!}
                    {!! Form::text('imss_number', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de afiliacion del IMSS']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('fonacot_credit', '¿Crédito fonacot?') !!}
                    {!! Form::select('fonacot_credit', ['si' => 'Si', 'no' => 'No'], null, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                </div> 
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('age', 'Edad') !!}
                    {!! Form::number('age', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la edad']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('fiscal_postal_code', 'CP Fiscal') !!}
                    {!! Form::text('fiscal_postal_code', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el codigo postal fiscal']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('discount_credit_number', 'N° credito descuento') !!}
                    {!! Form::text('discount_credit_number', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito descuento']) !!}
                </div> 
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('address', 'Direccion') !!}
                    {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la direccion']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('position', 'Puesto') !!}
                    {!! Form::text('position', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el puesto']) !!}
                </div>
                
                <div class="col-sm ">
                {!! Form::label('home_references', 'Referencia domicilio') !!}
                    {!! Form::text('home_references',null, ['class' => 'form-control', 'placeholder' => 'Ingrese las referencias del domicilio']) !!}
                </div> 
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('street', 'Calle') !!}
                    {!! Form::text('street', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la calle']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('area', 'Area') !!}
                    {!! Form::text('area', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el area']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('house_characteristics', 'Caracteristicas de la casa') !!}
                    {!! Form::text('house_characteristics',null, ['class' => 'form-control', 'placeholder' => 'Ingrese las caracteristicas de la casa']) !!}
                </div>
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('colony', 'Colonia') !!}
                    {!! Form::text('colony', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la colonia']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('salary_sd', 'Sueldo') !!}
                    {!! Form::text('salary_sd', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el sueldo neto']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('beneficiaries', 'Beneficiarios') !!}

                    <div class="row g-0 text-center">
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('beneficiary1', null, ['class' => 'form-control', 'placeholder' => 'primer beneficiario']) !!} 
                        </div>
                        <div class="col-6 col-md-4">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">%</span>
                                </div>
                                {!! Form::text('porcentage1', null, ['class' => 'form-control', 'placeholder' => 'Total']) !!} 
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('delegation', 'Delegacion o municipio') !!}
                    {!! Form::text('delegation', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la delegacion o municipio']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('horary', 'Horario') !!}
                    {!! Form::text('horary', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el horario ']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('', 'Beneficiario 2 (opcional)') !!}
                    <div class="row g-0 text-center">
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('beneficiary2', null, ['class' => 'form-control', 'placeholder' => 'segundo beneficiario']) !!} 
                        </div>
                        <div class="col-6 col-md-4">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">%</span>
                                </div>
                                {!! Form::text('porcentage2', null, ['class' => 'form-control', 'placeholder' => 'Total']) !!} 
                            </div>
                        </div>
                    </div>
                </div> 
                
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('postal_code', 'CP') !!}
                    {!! Form::text('postal_code', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la codigo postal']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('date_admission', 'Fecha de ingreso') !!}
                    {!! Form::date('date_admission', null, ['class' => 'form-control', 'placeholder' => 'Ingrese fecha de ingreso']) !!}
                </div>

                <div class="col-sm ">
                {!! Form::label('', 'Beneficiario 3 (opcional)') !!}
                <div class="row g-0 text-center">
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('beneficiary3', null, ['class' => 'form-control', 'placeholder' => 'tercer beneficiario']) !!} 
                        </div>
                        <div class="col-6 col-md-4">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">%</span>
                                </div>
                                {!! Form::text('porcentage3', null, ['class' => 'form-control', 'placeholder' => 'Total']) !!} 
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
        

        {!! Form::submit('GUARDAR', ['class' => 'btnCreate mt-4']) !!}
    </div>

    {!! Form::close() !!}
   
    </div>
@stop

@section('styles')
   <style>
        .text-info{
            display: none;
        }
        .fa-info-circle{
            margin-left: 8px;
            color: #1A346B;
        }

        .fa-info-circle:hover {
            margin-left: 8px;
            color: #0084C3;
        }
      
        #icon-text {
            display: none;
            margin-left: 16px;
            color: #fff;
            background-color: #1A346B;
            padding: 0 12px 0 12px;
            border-radius: 10px;
            font-size: 14px;
        }

        #content:hover~#icon-text{
            display: block;
        }
   </style>
@endsection


