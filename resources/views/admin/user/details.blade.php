@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row">
                <a  href="{{ route('admin.users.edit', ['user' => $user->id]) }}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
                </a>
                <h3 style="margin-left:16px;" class="separator">Información Adicional</h3> 
            </div>
                        
            <div class="d-flex">
                <button type="button" class="btn btn-success m-1" data-bs-toggle="modal"  data-bs-target="#modalAdd"><i class="bi bi-plus-lg"></i>Agregar documento</button>
            </div>
        </div>
    </div>
    <div class="card-body">

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        <br>
    @endif
    
    <div class="alert alert-light" role="alert">
        Ingresa la información adicional del colaborador, puedes omitir los campos si no cuentas con dicha información.
    </div>

        {!! Form::open(['route' => 'admin.user.updateUserDetails', 'enctype' => 'multipart/form-data']) !!}

            <h5>Información Adicional</h5>
            <div class="row form-group">
                {!! Form::text('user_id', isset($user->id) ? $user->id : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la nacionalidad', 'hidden']) !!}

                <div class="col-sm ">
                    {!! Form::label('civil_status', 'Estado civil',  ['class'=>'required']) !!}
                    {!! Form::select('civil_status', ['soltero' => 'Soltero(a)', 'casado' => 'Casado(a)',  'divorciado' => 'Divorciado(a)', 'viudo' => 'Viudo(a)', 'conviviente' => 'Conviviente' ], isset($user_details->civil_status) ? $user_details->civil_status : null , ['class' => 'form-control', 'placeholder' => 'Seleccione el estado civil']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('age', 'Edad', ['class'=>'required']) !!}
                    {!! Form::number('age', isset($age) ? $age : null , ['class' => 'form-control', 'placeholder' => 'Ingrese la edad']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('gender', 'Genero',  ['class'=>'required']) !!}
                    {!! Form::select('gender',  ['Femenino' => 'Femenino', 'Masculino' => 'Masculino', 'Otro' => 'Otro'], isset($user_details->gender) ? $user_details->gender : null , ['class' => 'form-control', 'placeholder' => 'Seleccione el genero']) !!}
                </div>  
                
            </div>

            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('nacionality', 'Nacionalidad', ['class'=>'required']) !!}
                    {!! Form::text('nacionality', isset($user_details->nacionality) ? $user_details->nacionality : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la nacionalidad']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('id_credential', 'ID credencial', ['class'=>'required']) !!}
                    {!! Form::text('id_credential', isset($user_details->id_credential) ? $user_details->id_credential : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el ID de la credencial']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('rfc', 'RFC', ['class'=>'required']) !!}
                    {!! Form::text('rfc',  isset($user_details->rfc) ? $user_details->rfc : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el RFC']) !!}
                </div>

            </div>
            <div class="row form-group">
                
                <div class="col-sm">
                    {!! Form::label('nss', 'NSS', ['class' => 'required']) !!}
                    {!! Form::text('nss', isset($user_details->nss) ? $user_details->nss : null , ['class' => 'form-control','placeholder' => 'Ingrese el NSS']) !!}
                </div> 

                <div class="col-sm">
                    {!! Form::label('curp', 'CURP', ['class' => 'required']) !!}
                    {!! Form::text('curp', isset($user_details->curp) ? $user_details->curp : null , ['class' => 'form-control','placeholder' => 'Ingrese el CURP']) !!}
                </div>

                <div class="col-sm">
                    {!! Form::label('phone', 'Celular', ['class' => 'required']) !!}
                    {!! Form::text('phone', isset($user_details->phone) ? $user_details->phone : null , ['class' => 'form-control','placeholder' => 'Ingrese el numero de teléfono']) !!}
                </div>
            
            </div>

            <div class="row form-group">
               
                <div class="col-sm">
                    {!! Form::label('message_phone', 'Telefono de recados', ['class' => 'required']) !!}
                    {!! Form::text('message_phone', isset($user_details->message_phone) ? $user_details->message_phone : null, ['class' => 'form-control','placeholder' => 'Ingrese el teléfono de recados']) !!}
                </div>

                <div class="col-sm">
                    {!! Form::label('email', 'Correo electrónico', ['class' => 'required']) !!}
                    {!! Form::text('email', isset($user_details->email) ? $user_details->email : null, ['class' => 'form-control','placeholder' => 'Ingrese el correo electrónico']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('fathers_name', 'Nombre del padre') !!}
                    {!! Form::text('fathers_name', isset($user_details->fathers_name) ? $user_details->fathers_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo del padre']) !!}
                </div>
            </div>

            <div class="row form-group">
                

                <div class="col-sm ">
                    {!! Form::label('mothers_name', 'Nombre de la madre') !!}
                    {!! Form::text('mothers_name', isset($user_details->mothers_name) ? $user_details->mothers_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo de la madre']) !!}
                </div>

                <div class="col-sm ">

                </div>

                <div class="col-sm ">

                </div>

            </div>
            <br><br>

            <h5>Localidad</h5>

            <div class="row form-group">
                <div class="col-sm">
                    {!! Form::label('full_address', 'Domicilio completo', ['class' => 'required']) !!}
                    {!! Form::text('full_address', isset($user_details->full_address) ? $user_details->full_address : null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('place_of_birth', 'Lugar de nacimiento', ['class'=>'required'] ) !!}
                    {!! Form::text('place_of_birth',isset($user_details->place_of_birth) ? $user_details->place_of_birth : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el lugar de nacimiento']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('street', 'Calle', ['class'=>'required']) !!}
                    {!! Form::text('street', isset($user_details->street) ? $user_details->street : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la calle']) !!}
                </div>

               
  
            </div>

            <div class="row form-group">
             
                <div class="col-sm ">
                    {!! Form::label('colony', 'Colonia', ['class'=>'required']) !!}
                    {!! Form::text('colony', isset($user_details->colony) ? $user_details->colony : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la colonia']) !!}
                </div>  
                <div class="col-sm ">
                    {!! Form::label('delegation', 'Delegacion o municipio',  ['class'=>'required']) !!}
                    {!! Form::text('delegation', isset($user_details->delegation) ? $user_details->delegation : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la delegacion o municipio']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('postal_code', 'CP', ['class' => 'required']) !!}
                    {!! Form::text('postal_code', isset($user_details->postal_code) ? $user_details->postal_code : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la codigo postal']) !!}
                </div>
            </div>

            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('fiscal_postal_code', 'CP fiscal', ['class' => 'required']) !!}
                    {!! Form::text('fiscal_postal_code', isset($user_details->fiscal_postal_code) ? $user_details->fiscal_postal_code : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el codigo postal fiscal']) !!}
                </div> 
                <div class="col-sm ">
                    {!! Form::label('home_phone', 'Telefono de casa', ['class' => 'required']) !!}
                    {!! Form::number('home_phone', isset($user_details->home_phone) ? $user_details->home_phone : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono de casa']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('home_references', 'Referencia domicilio', ['class' => 'required']) !!}
                    {!! Form::text('home_references', isset($user_details->home_references) ? $user_details->home_references : null, ['class' => 'form-control', 'placeholder' => 'Ingrese las referencias del domicilio']) !!}
                </div>
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('house_characteristics', 'Caracteristicas de la casa', ['class' => 'required']) !!}
                    {!! Form::text('house_characteristics', isset($user_details->house_characteristics) ? $user_details->house_characteristics : null, ['class' => 'form-control', 'placeholder' => 'Ingrese las caracteristicas de la casa']) !!}
                </div>

                <div class="col-sm">

                </div>
                <div class="col-sm">

                </div>
            </div>

            <br><br>

            <h5>Detalles de Ingreso</h5>
            <div class="row form-group">
                
                <div class="col-sm ">
                    {!! Form::label('contract_duration', 'Duración contrato', ['class' => 'required']) !!}
                    {!! Form::select('contract_duration', ['indefinido' => 'Tiempo indefinido','3' => '3 Meses'], isset($user_details->contract_duration) ? $user_details->contract_duration : null,['class' => 'form-control', 'placeholder' => 'Selecciona duracion']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('month_salary_gross', 'Salario bruto mensual', ['class' => 'required']) !!}
                    {!! Form::text('month_salary_gross',  isset($user_details->month_salary_gross) ? $user_details->month_salary_gross : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario bruto mensual']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('month_salary_net', 'Salario neto mensual', ['class' => 'required']) !!}
                    {!! Form::text('month_salary_net', isset($user_details->month_salary_net) ? $user_details->month_salary_net : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario neto mensual']) !!}
                </div>
               
            </div>

            <div class="row form-group">
                
                <div class="col-sm ">
                    {!! Form::label('daily_salary', 'Salario diario', ['class' => 'required']) !!}
                    {!! Form::text('daily_salary', isset($user_details->daily_salary) ? $user_details->daily_salary : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario diario']) !!}
                </div> 

                <div class="col-sm ">
                    {!! Form::label('daily_salary_letter', 'Salario diario en letra', ['class' => 'required']) !!}
                    {!! Form::text('daily_salary_letter', isset($user_details->daily_salary_letter) ? $user_details->daily_salary_letter : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario diario en letra']) !!}
                </div>


                <div class="col-sm">

                </div>

            </div>
            <br><br>

            <h5>Información Bancaria</h5>

            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('bank_name', 'Banco') !!}
                    {!! Form::text('bank_name',  isset($user_details->bank_name) ? $user_details->bank_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del banco']) !!}
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('card_number', 'N° tarjeta/cuenta') !!}
                    {!! Form::text('card_number', isset($user_details->card_number) ? $user_details->card_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de cuenta']) !!}
                </div>                
            </div>

            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('infonavit_credit', 'Credito infonavit') !!}
                    {!! Form::select('infonavit_credit', ['si' => 'Si', 'no' => 'No'], isset($user_details->infonavit_credit) ? $user_details->infonavit_credit : null, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('factor_credit_number', 'N° credito factor') !!}
                    {!! Form::text('factor_credit_number', isset($user_details->factor_credit_number) ? $user_details->factor_credit_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito factor']) !!}
                </div>
                
            </div>

            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('fonacot_credit', '¿Crédito fonacot?') !!}
                    {!! Form::select('fonacot_credit', ['si' => 'Si', 'no' => 'No'], isset($user_details->fonacot_credit) ? $user_details->fonacot_credit : null, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('discount_credit_number', 'N° credito descuento') !!}
                    {!! Form::text('discount_credit_number',  isset($user_details->discount_credit_number) ? $user_details->discount_credit_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito descuento']) !!}
                </div>
            </div>

            <br><br>

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

        .stepwizard-step p {
            margin-top: 0px;
            color:#666;
        }
        .stepwizard-row {
            display: table-row;
        }
        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }
        .btn-default{
            background-color: #0084C3;
        }

        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content:" ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-index: 0;
        }
        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
            color: #fff;
        }

        .no-selected{
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
            color: #000;
            background-color: #fff;
            border-color: #0084C3;
        }
   </style>
@endsection
