@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex flex-row" >
            <a  href="{{ route('rh.postulants') }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
            </a>
            <h3 style="margin-left:16px;" class="separator">Editar información </h3> 
        </div>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        {!! Form::model($postulant, ['route' => ['rh.updatePostulant', $postulant], 'method' => 'put','enctype' => 'multipart/form-data']) !!}

        <h5>Información personal</h5>
        <p></p>
        <div class="row form-group">
                <div class="col-sm">
                    {!! Form::text('postulant_id', $postulant->id,['class' => 'form-control', 'hidden']) !!}  

                    {!! Form::label('name', 'Nombre') !!}
                    {!! Form::text('name', $postulant->name, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
                
                <div class="col-sm">
                    {!! Form::label('lastname', 'Apellidos') !!}
                    {!! Form::text('lastname', $postulant->lastname, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', ['postulante' => 'Postulante', 'candidato' => 'Candidato',  'empleado' => 'Empleado', 'noseleccionado' => 'No seleccionado', ], $postulant->status, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante','id' => 'pstatus']) !!}
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
                {!! Form::label('mail', 'Correo') !!}
                {!! Form::text('mail', $postulant->mail, ['class' => 'form-control', 'placeholder' => 'Ingrese el correo']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('phone', 'Telefono') !!}
                {!! Form::text('phone', $postulant->phone, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono celular']) !!}
            </div>
              
            <div class="col-sm">
                {!! Form::label('cv', 'CV o solicitud elaborada (opcional)') !!}
                {!! Form::file('cv', ['class' => 'form-control']) !!}
            </div>  
        </div>
        <div class="row form-group">
            <div class="col-sm">
                {!! Form::label('company_id', 'Empresa de interes') !!}
                {!! Form::select('company_id', $companies, $postulant->company_id, ['class' => 'form-control']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('department_id', 'Departamento de interes') !!}
                {!! Form::select('department_id', $departments, $postulant->department_id, ['class' => 'form-control']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('department_id', 'Fecha de entrevista (opcional)') !!}
                <input type="datetime-local" id="meeting-time" value="{{$postulant->interview_date}}"
                name="interview_date" class="form-control">
            </div>
        </div>

        <br>

   
        <br>
        <div class="more-information"  @if( $postulant->status <> 'candidato') style="display:none"  @endif  id="more-information">
            <div class="d-flex flex-row"> 
                <h6>Información adicional</h6>
                <div id='content'>
                    <div id='icon'>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                    </div>
                </div>
                <div id='icon-text'>
                    La información adicional solo es requerida previo a la generación de la documentación de alta o cuando el status sea candidato
                </div>
            </div>

          
            
            <br>
            <h5>Información adicional</h5>
            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('cell_phone', 'Telefono celular') !!}
                    {!! Form::number('cell_phone', isset($postulant_details->cell_phone) ? $postulant_details->cell_phone : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numeor de telefono celular']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('home_phone', 'Telefono de casa') !!}
                    {!! Form::number('home_phone', isset($postulant_details->home_phone) ? $postulant_details->home_phone : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono de casa']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('birthdate', 'Fecha de nacimiento') !!}
                    {!! Form::date('birthdate', isset($postulant_details->birthdate) ? $postulant_details->birthdate : null, ['class' => 'form-control']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('curp', 'CURP') !!}
                    {!! Form::text('curp', isset($postulant_details->curp) ? $postulant_details->curp : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el CURP']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('rfc', 'RFC') !!}
                    {!! Form::text('rfc', isset($postulant_details->rfc) ? $postulant_details->rfc : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el RFC']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('imss_number', 'N° afiliacion IMSS') !!}
                    {!! Form::text('imss_number', isset($postulant_details->imss_number) ? $postulant_details->imss_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de afiliacion del IMSS']) !!} 
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('nacionality', 'Nacionalidad') !!}
                    {!! Form::text('nacionality', isset($postulant_details->nacionality) ? $postulant_details->nacionality : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la nacionalidad']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('id_credential', 'ID credencial') !!}
                    {!! Form::text('id_credential', isset($postulant_details->id_credential) ? $postulant_details->id_credential : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el ID de la credencial']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('gender', 'Genero') !!}
                    {!! Form::select('gender',  ['Femenino' => 'Femenino', 'Masculino' => 'Masculino', 'Otro' => 'Otro'], isset($postulant_details->gender) ? $postulant_details->gender : null, ['class' => 'form-control', 'placeholder' => 'Seleccione el genero']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('civil_status', 'Estado civil') !!}
                    {!! Form::select('civil_status', ['soltero' => 'Soltero(a)', 'casado' => 'Casado(a)',  'divorciado' => 'Divorciado(a)', 'viudo' => 'Viudo(a)', 'conviviente' => 'Conviviente' ], isset($postulant_details->civil_status) ? $postulant_details->civil_status : null ,['class' => 'form-control', 'placeholder' => 'Ingrese la estado civil']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('age', 'Edad') !!}
                    {!! Form::number('age', isset($postulant_details->age) ? $postulant_details->age : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la edad']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('date_admission', 'Fecha de ingreso') !!}
                    {!! Form::date('date_admission', isset($postulant_details->date_admission) ? $postulant_details->date_admission : null, ['class' => 'form-control', 'placeholder' => 'Ingrese fecha de ingreso']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('contract_duration', 'Duración contrato') !!}
                    {!! Form::select('contract_duration', ['indefinido' => 'Tiempo indefinido', '1' => '1 Mes',  '2' => '2 Meses', '3' => '3 Meses', '4' => '4 Meses' ,  '5' => '5 Meses' , '6' => '6 Meses' ], isset($postulant_details->contract_duration) ? $postulant_details->contract_duration : null ,['class' => 'form-control', 'placeholder' => 'Selecciona duracion']) !!}
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
                    {!! Form::text('position', isset($postulant_details->position) ? $postulant_details->position : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el puesto']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('position_objetive', 'Objetivo del puesto') !!}
                    {!! Form::text('position_objetive', isset($postulant_details->position_objetive) ? $postulant_details->position_objetive : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el objetivo del puesto']) !!}
                </div> 
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('month_salary_gross', 'Salario bruto mensual') !!}
                    {!! Form::text('month_salary_gross', isset($postulant_details->month_salary_gross) ? $postulant_details->month_salary_gross : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario bruto mensual']) !!}
                </div> 

                <div class="col-sm ">
                    {!! Form::label('month_salary_net', 'Salario neto mensual') !!}
                    {!! Form::text('month_salary_net', isset($postulant_details->month_salary_net) ? $postulant_details->month_salary_net : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario neto mensual']) !!}
                </div>
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('daily_salary', 'Salario diario') !!}
                    {!! Form::text('daily_salary', isset($postulant_details->daily_salary) ? $postulant_details->daily_salary : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario diario']) !!}
                </div> 

                <div class="col-sm ">
                    {!! Form::label('daily_salary_letter', 'Salario diario en letra') !!}
                    {!! Form::text('daily_salary_letter', isset($postulant_details->daily_salary_letter) ? $postulant_details->daily_salary_letter : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario diario en letra']) !!}
                </div>
            </div>
            <br>

            <h5>Ubicación</h5>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('address', 'Direccion') !!}
                    {!! Form::text('address', isset($postulant_details->address) ? $postulant_details->address : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la direccion']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('street', 'Calle') !!}
                    {!! Form::text('street', isset($postulant_details->street) ? $postulant_details->street : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la calle']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('colony', 'Colonia') !!}
                    {!! Form::text('colony', isset($postulant_details->colony) ? $postulant_details->colony : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la colonia']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('delegation', 'Delegacion o municipio') !!}
                    {!! Form::text('delegation',  isset($postulant_details->delegation) ? $postulant_details->delegation : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la delegacion o municipio']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('home_references', 'Referencia domicilio') !!}
                    {!! Form::text('home_references', isset($postulant_details->home_references) ? $postulant_details->home_references : null, ['class' => 'form-control', 'placeholder' => 'Ingrese las referencias del domicilio']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('house_characteristics', 'Caracteristicas de la casa') !!}
                    {!! Form::text('house_characteristics', isset($postulant_details->house_characteristics) ? $postulant_details->house_characteristics : null, ['class' => 'form-control', 'placeholder' => 'Ingrese las caracteristicas de la casa']) !!}
                </div>  
            </div>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('place_of_birth', 'Lugar de nacimiento') !!}
                    {!! Form::text('place_of_birth', isset($postulant_details->place_of_birth) ? $postulant_details->place_of_birth : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el lugar de nacimiento']) !!}
                </div>

                <div class="col-sm ">
                    {!! Form::label('postal_code', 'CP') !!}
                    {!! Form::text('postal_code', isset($postulant_details->postal_code) ? $postulant_details->postal_code : null, ['class' => 'form-control', 'placeholder' => 'Ingrese la codigo postal']) !!}
                </div>
                
                <div class="col-sm ">
                    {!! Form::label('fiscal_postal_code', 'CP fiscal') !!}
                    {!! Form::text('fiscal_postal_code', isset($postulant_details->fiscal_postal_code) ? $postulant_details->fiscal_postal_code : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el codigo postal fiscal']) !!}
                </div>  
            </div>
            <br>


            <h5>Información familiar</h5>

            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('fathers_name', 'Nombre del padre') !!}
                    {!! Form::text('fathers_name', isset($postulant_details->fathers_name) ? $postulant_details->fathers_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo del padre']) !!}
                </div>

                <div class="col-sm ">
                {!! Form::label('mothers_name', 'Nombre de la madre') !!}
                    {!! Form::text('mothers_name', isset($postulant_details->mothers_name) ? $postulant_details->mothers_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo de la madre']) !!}
                </div>
                
            </div>



            <br>
            <h5>Información bancaria</h5>
        
            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('bank_name', 'Banco') !!}
                    {!! Form::text('bank_name', isset($postulant_details->bank_name) ? $postulant_details->bank_name : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del banco']) !!}
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('card_number', 'N° tarjeta/cuenta') !!}
                    {!! Form::text('card_number', isset($postulant_details->card_number) ? $postulant_details->card_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de cuenta']) !!}
                </div>
                
            </div>

            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('infonavit_credit', 'Credito infonavit') !!}
                    {!! Form::select('infonavit_credit', ['si' => 'Si', 'no' => 'No'], isset($postulant_details->infonavit_credit) ? $postulant_details->infonavit_credit : null, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('factor_credit_number', 'N° credito factor') !!}
                    {!! Form::text('factor_credit_number', isset($postulant_details->factor_credit_number) ? $postulant_details->factor_credit_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito factor']) !!}
                </div>
                
            </div>

            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('fonacot_credit', '¿Crédito fonacot?') !!}
                    {!! Form::select('fonacot_credit', ['si' => 'Si', 'no' => 'No'], isset($postulant_details->fonacot_credit) ? $postulant_details->fonacot_credit : null, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('discount_credit_number', 'N° credito descuento') !!}
                    {!! Form::text('discount_credit_number', isset($postulant_details->discount_credit_number) ? $postulant_details->discount_credit_number : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito descuento']) !!}
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


@section('scripts')
    <script>
        const postulantStatus = document.getElementById("pstatus");
        postulantStatus.addEventListener("change", statusChange);

        function statusChange(event) {
            const currentValue = event.target.value;
            console.log(currentValue);
            if(currentValue == 'candidato'){
                document.getElementById('more-information').style.display = 'block';
            }else{
                document.getElementById('more-information').style.display = 'none';
            }
        }
    </script>
@stop