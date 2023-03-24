@extends('layouts.app')

@section('content')
    <div class="card-header">

    <div class="d-flex justify-content-between">
        <h3>Información adicional</h3>
        <div>                
            <form 
                action="{{ route('rh.scanDocuments', ['id' => $user_id]) }}"
                method="GET">
                 @csrf
                <button type="submit" class="btn btn-primary"> 
                    <i class="fa fa-file-text me-2" aria-hidden="true"></i>  
                    Documentos guardados
                </button>
            </form>
        </div>
    </div>
        
    </div>
    <div class="card-body">
            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        {!! Form::open(['route' => 'rh.createMoreInformation', 'enctype' => 'multipart/form-data']) !!}
        
        <input type="text" name="user_id" value={{$user_id}} hidden>  
            <br>
            <h5>Información personal</h5>
            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('cell_phone', 'Telefono celular') !!}
                    {!! Form::number('cell_phone', isset($user_details->cell_phone) ? $user_details->cell_phone : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numeor de telefono celular']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('home_phone', 'Telefono de casa') !!}
                    {!! Form::number('home_phone', isset($user_details->home_phone) ? $user_details->home_phone : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono de casa']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('birthdate', 'Fecha de nacimiento') !!}
                    {!! Form::date('birthdate', isset($user_details->birthdate) ? $user_details->birthdate : null, ['class' => 'form-control']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('curp', 'CURP') !!}
                    {!! Form::text('curp', isset($user_details->curp) ? $user_details->curp : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el CURP']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('rfc', 'RFC') !!}
                    {!! Form::text('rfc', isset($user_details->rfc) ? $user_details->rfc : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el RFC']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('imss_number', 'N° afiliacion IMSS') !!}
                    {!! Form::text('imss_number', isset($user_details->imss_number) ? $user_details->imss_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de afiliacion del IMSS']) !!} 
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('nacionality', 'Nacionalidad') !!}
                    {!! Form::text('nacionality', isset($user_details->nacionality) ? $user_details->nacionality : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la nacionalidad']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('id_credential', 'ID credencial') !!}
                    {!! Form::text('id_credential', isset($user_details->id_credential) ? $user_details->id_credential : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el ID de la credencial']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('gender', 'Genero') !!}
                    {!! Form::select('gender',  ['Femenino' => 'Femenino', 'Masculino' => 'Masculino', 'Otro' => 'Otro'], isset($user_details->gender) ? $user_details->gender : null, ['class' => 'form-control', 'placeholder' => 'Seleccione el genero']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('civil_status', 'Estado civil') !!}
                    {!! Form::select('civil_status', ['soltero' => 'Soltero(a)', 'casado' => 'Casado(a)',  'divorciado' => 'Divorciado(a)', 'viudo' => 'Viudo(a)', 'conviviente' => 'Conviviente' ], isset($user_details->civil_status) ? $user_details->civil_status : null ,['class' => 'form-control', 'placeholder' => 'Ingrese la estado civil']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('age', 'Edad') !!}
                    {!! Form::number('age', isset($user_details->age) ? $user_details->age : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la edad']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('date_admission', 'Fecha de ingreso') !!}
                    {!! Form::date('date_admission', isset($user_details->date_admission) ? $user_details->date_admission : null, ['class' => 'form-control', 'placeholder' => 'Ingrese fecha de ingreso']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('contract_duration', 'Duración contrato') !!}
                    {!! Form::select('contract_duration', ['indefinido' => 'Tiempo indefinido', '1' => '1 Mes',  '2' => '2 Meses', '3' => '3 Meses', '4' => '4 Meses' ,  '5' => '5 Meses' , '6' => '6 Meses' ], isset($user_details->contract_duration) ? $user_details->contract_duration : null ,['class' => 'form-control', 'placeholder' => 'Selecciona duracion']) !!}
                </div>

                <div class="col-sm ">

                </div>
                
                <div class="col-sm ">

                </div>  
            </div>

            <br>

            <h5>Detalles del puesto</h5>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('position', 'Puesto') !!}
                    {!! Form::text('position', isset($user_details->position) ? $user_details->position : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el puesto']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('position_objetive', 'Objetivo del puesto') !!}
                    {!! Form::text('position_objetive', isset($user_details->position_objetive) ? $user_details->position_objetive : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el objetivo del puesto']) !!}
                </div> 
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('month_salary_gross', 'Salario bruto mensual') !!}
                    {!! Form::text('month_salary_gross', isset($user_details->month_salary_gross) ? $user_details->month_salary_gross : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario bruto mensual']) !!}
                </div> 

                <div class="col-sm ">
                    {!! Form::label('month_salary_net', 'Salario neto mensual') !!}
                    {!! Form::text('month_salary_net', isset($user_details->month_salary_net) ? $user_details->month_salary_net : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario neto mensual']) !!}
                </div>
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('daily_salary', 'Salario diario') !!}
                    {!! Form::text('daily_salary', isset($user_details->daily_salary) ? $user_details->daily_salary : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario diario']) !!}
                </div> 

                <div class="col-sm ">
                    {!! Form::label('daily_salary_letter', 'Salario diario en letra') !!}
                    {!! Form::text('daily_salary_letter', isset($user_details->daily_salary_letter) ? $user_details->daily_salary_letter : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario diario en letra']) !!}
                </div>
            </div>
            <br>

            <h5>Ubicación</h5>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('address', 'Direccion') !!}
                    {!! Form::text('address', isset($user_details->address) ? $user_details->address : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la direccion']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('street', 'Calle') !!}
                    {!! Form::text('street', isset($user_details->street) ? $user_details->street : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la calle']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('colony', 'Colonia') !!}
                    {!! Form::text('colony', isset($user_details->colony) ? $user_details->colony : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la colonia']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('delegation', 'Delegacion o municipio') !!}
                    {!! Form::text('delegation',  isset($user_details->delegation) ? $user_details->delegation : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la delegacion o municipio']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('home_references', 'Referencia domicilio') !!}
                    {!! Form::text('home_references', isset($user_details->home_references) ? $user_details->home_references : null, ['class' => 'form-control', 'placeholder' => 'Ingrese las referencias del domicilio']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('house_characteristics', 'Caracteristicas de la casa') !!}
                    {!! Form::text('house_characteristics', isset($user_details->house_characteristics) ? $user_details->house_characteristics : null, ['class' => 'form-control', 'placeholder' => 'Ingrese las caracteristicas de la casa']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('place_of_birth', 'Lugar de nacimiento') !!}
                    {!! Form::text('place_of_birth', isset($user_details->place_of_birth) ? $user_details->place_of_birth : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el lugar de nacimiento']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('postal_code', 'CP') !!}
                    {!! Form::text('postal_code', isset($user_details->postal_code) ? $user_details->postal_code : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la codigo postal']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('fiscal_postal_code', 'CP fiscal') !!}
                    {!! Form::text('fiscal_postal_code', isset($user_details->fiscal_postal_code) ? $user_details->fiscal_postal_code : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el codigo postal fiscal']) !!}
                </div>  
            </div>
            <br>


            <h5>Información familiar</h5>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('fathers_name', 'Nombre del padre') !!}
                    {!! Form::text('fathers_name', isset($user_details->fathers_name) ? $user_details->fathers_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo del padre']) !!}
                </div>

                <div class="col-sm ">
                {!! Form::label('mothers_name', 'Nombre de la madre') !!}
                    {!! Form::text('mothers_name', isset($user_details->mothers_name) ? $user_details->mothers_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo de la madre']) !!}
                </div>
                
            </div>

            <br>
            <h5>Información bancaria</h5>
        
            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('bank_name', 'Banco') !!}
                    {!! Form::text('bank_name', isset($user_details->bank_name) ? $user_details->bank_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del banco']) !!}
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
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
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
                    {!! Form::text('discount_credit_number', isset($user_details->discount_credit_number) ? $user_details->discount_credit_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito descuento']) !!}
                </div>
            </div>

            <br>

            <h5>Beneficiarios</h5>

            <div class="row form-group">
                <div class="col-sm">
                {!! Form::label('beneficiaries', 'Beneficiario 1') !!}

                <div class="row g-0 text-center">
                    <div class="col-sm-6 col-md-8">
                        {!! Form::text('beneficiary1',isset($postulant_beneficiaries[0]->name) ? $postulant_beneficiaries[0]->name : null, ['class' => 'form-control', 'placeholder' => 'primer beneficiario']) !!} 
                    </div>
                    <div class="col-6 col-md-4">

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            {!! Form::text('porcentage1',isset($postulant_beneficiaries[0]->porcentage) ? $postulant_beneficiaries[0]->porcentage : null, ['class' => 'form-control', 'placeholder' => 'Total']) !!} 
                        </div>
                    </div>
                </div>
                </div>

                <div class="col-sm ">
                    {!! Form::label('', 'Beneficiario 2 ') !!}
                    <div class="row g-0 text-center">
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('beneficiary2',isset($postulant_beneficiaries[1]->name) ? $postulant_beneficiaries[1]->name : null, ['class' => 'form-control', 'placeholder' => 'segundo beneficiario']) !!} 
                        </div>
                        <div class="col-6 col-md-4">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">%</span>
                                </div>
                                {!! Form::text('porcentage2',isset($postulant_beneficiaries[1]->porcentage) ? $postulant_beneficiaries[1]->porcentage : null, ['class' => 'form-control', 'placeholder' => 'Total']) !!} 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('', 'Beneficiario 3 ') !!}
                    <div class="row g-0 text-center">
                            <div class="col-sm-6 col-md-8">
                                {!! Form::text('beneficiary3',isset($postulant_beneficiaries[2]->name) ? $postulant_beneficiaries[2]->name : null, ['class' => 'form-control', 'placeholder' => 'tercer beneficiario']) !!} 
                            </div>
                            <div class="col-6 col-md-4">

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    {!! Form::text('porcentage3',isset($postulant_beneficiaries[2]->porcentage) ? $postulant_beneficiaries[2]->porcentage : null, ['class' => 'form-control', 'placeholder' => 'Total']) !!} 
                                </div>
                            </div>
                        </div>
                    </div>

            <div class="col-sm "> 
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


