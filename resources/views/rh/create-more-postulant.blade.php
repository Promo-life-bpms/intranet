@extends('layouts.app')

@section('content')
    <div class="card-header">

    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row" >
            <a  href="{{ route('rh.editPostulant', ['postulant_id' => $postulant->id]) }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
            </a>
            <h3 style="margin-left:16px;" class="separator">Recepción de Documentos</h3> 
        </div>
        <div>   
            @if ($postulant->status == 'candidato')
           
                <button type="submit" class="btn btn-secondary" onclick="wrongAlert()"> 
                    Kit legal de Ingreso
                    <i class="ms-2 fa fa-arrow-right" aria-hidden="true"></i>
                </button>
            
            @else
            <form 
                action="{{ route('rh.createPostulantDocumentation', ['postulant_id' => $postulant->id]) }}"
                method="GET">
                 @csrf
                <button type="submit" class="btn btn-primary"> 
                    Kit legal de Ingreso
                    <i class="ms-2 fa fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>
            @endif             
            
        </div>
    </div>
        
    </div>
    <div class="card-body">

    <div class="container" >
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3" style="width: 16.6%;">  
                    <a href="#step-1" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">1</a>
                    <p><small>Alta de Candidato</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-2" type="button" class="btn btn-default btn-circle " disabled="disabled">2</a>
                    <p><small>Recepción de Documentos</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-3" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">3</a>
                    <p><small>Kit legal de Ingreso</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle  no-selected" disabled="disabled">4</a>
                    <p><small>Plan de Trabajo</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">5</a>
                    <p><small>Kit Legal Firmado</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">6</a>
                    <p><small>Alta de Colaborador</small></p>
                </div>
            </div>
        </div>
    </div>
    <br>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
            <br>
        </div>
    @endif
    
    <br>
        {!! Form::open(['route' => 'rh.storeMoreInformation', 'enctype' => 'multipart/form-data']) !!}
        
            <input type="text" name="postulant_id" value={{$postulant->id}} hidden>  
            <div class="alert alert-light" role="alert">
                Una vez recibidos los documentos, es necesario llenar la siguiente información para la generación del <b>Kit Legal de Ingreso</b>.
            </div>
            <br>
            <h5>Información Adicional</h5>
            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('civil_status', 'Estado civil',  ['class'=>'required']) !!}
                    {!! Form::select('civil_status', ['soltero' => 'Soltero(a)', 'casado' => 'Casado(a)',  'divorciado' => 'Divorciado(a)', 'viudo' => 'Viudo(a)', 'conviviente' => 'Conviviente' ], $postulant->civil_status ,['class' => 'form-control', 'placeholder' => 'Seleccione el estado civil']) !!}
                    @error('civil_status')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('age', 'Edad', ['class'=>'required']) !!}
                    {!! Form::number('age', $postulant->age, ['class' => 'form-control', 'placeholder' => 'Ingrese la edad']) !!}
                    @error('age')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('gender', 'Genero',  ['class'=>'required']) !!}
                    {!! Form::select('gender',  ['Femenino' => 'Femenino', 'Masculino' => 'Masculino', 'Otro' => 'Otro'], $postulant->gender , ['class' => 'form-control', 'placeholder' => 'Seleccione el genero']) !!}
                    @error('gender')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>  
                
            </div>

            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('nacionality', 'Nacionalidad', ['class'=>'required']) !!}
                    {!! Form::text('nacionality', $postulant->nacionality, ['class' => 'form-control', 'placeholder' => 'Ingrese la nacionalidad']) !!}
                    @error('nacionality')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('id_credential', 'ID credencial', ['class'=>'required']) !!}
                    {!! Form::text('id_credential', $postulant->id_credential, ['class' => 'form-control', 'placeholder' => 'Ingrese el ID de la credencial']) !!}
                    @error('id_credential')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('rfc', 'RFC', ['class'=>'required']) !!}
                    {!! Form::text('rfc', $postulant->rfc, ['class' => 'form-control', 'placeholder' => 'Ingrese el RFC']) !!}
                    @error('rfc')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

            </div>
            <div class="row form-group">
                <div class="col-sm ">
                    {!! Form::label('fathers_name', 'Nombre del padre') !!}
                    {!! Form::text('fathers_name', $postulant->fathers_name, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo del padre']) !!}
                    @error('fathers_name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('mothers_name', 'Nombre de la madre') !!}
                    {!! Form::text('mothers_name', $postulant->mothers_name, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre completo de la madre']) !!}
                    @error('mothers_name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">

                </div>
            </div>

            <br><br>

            <h5>Localidad</h5>

            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('place_of_birth', 'Lugar de nacimiento', ['class'=>'required'] ) !!}
                    {!! Form::text('place_of_birth', $postulant->place_of_birth, ['class' => 'form-control', 'placeholder' => 'Ingrese el lugar de nacimiento']) !!}
                    @error('place_of_birth')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('street', 'Calle', ['class'=>'required']) !!}
                    {!! Form::text('street',  $postulant->street, ['class' => 'form-control', 'placeholder' => 'Ingrese la calle']) !!}
                    @error('street')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('colony', 'Colonia', ['class'=>'required']) !!}
                    {!! Form::text('colony', $postulant->colony, ['class' => 'form-control', 'placeholder' => 'Ingrese la colonia']) !!}
                    @error('colony')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>  
  
            </div>

            <div class="row form-group">
             
                <div class="col-sm ">
                    {!! Form::label('delegation', 'Delegacion o municipio',  ['class'=>'required']) !!}
                    {!! Form::text('delegation', $postulant->delegation, ['class' => 'form-control', 'placeholder' => 'Ingrese la delegacion o municipio']) !!}
                    @error('delegation')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('postal_code', 'CP', ['class' => 'required']) !!}
                    {!! Form::text('postal_code', $postulant->postal_code , ['class' => 'form-control', 'placeholder' => 'Ingrese la codigo postal']) !!}
                    @error('postal_code')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('fiscal_postal_code', 'CP fiscal', ['class' => 'required']) !!}
                    {!! Form::text('fiscal_postal_code', $postulant->fiscal_postal_code, ['class' => 'form-control', 'placeholder' => 'Ingrese el codigo postal fiscal']) !!}
                    @error('fiscal_postal_code')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div> 
                
            </div>

            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('home_phone', 'Telefono de casa', ['class' => 'required']) !!}
                    {!! Form::number('home_phone', $postulant->home_phone, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono de casa']) !!}
                    @error('home_phones')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('home_references', 'Referencia domicilio', ['class' => 'required']) !!}
                    {!! Form::text('home_references', $postulant->home_references, ['class' => 'form-control', 'placeholder' => 'Ingrese las referencias del domicilio']) !!}
                    @error('home_references')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('house_characteristics', 'Caracteristicas de la casa', ['class' => 'required']) !!}
                    {!! Form::text('house_characteristics', $postulant->house_characteristics, ['class' => 'form-control', 'placeholder' => 'Ingrese las caracteristicas de la casa']) !!}
                    @error('house_characteristics')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

            </div>

            <br><br>

            <h5>Detalles de Ingreso</h5>
            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('date_admission', 'Fecha prevista de ingreso', ['class' => 'required']) !!}
                    {!! Form::date('date_admission', $postulant->date_admission, ['class' => 'form-control', 'placeholder' => 'Ingrese fecha de ingreso']) !!}
                    @error('date_admission')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>  

                <div class="col-sm ">
                    {!! Form::label('contract_duration', 'Duración contrato', ['class' => 'required']) !!}
                    {!! Form::select('contract_duration', ['indefinido' => 'Tiempo indefinido','3' => '3 Meses'], $postulant->contract_duration,['class' => 'form-control', 'placeholder' => 'Selecciona duracion']) !!}
                    @error('contract_duration')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm ">
                    {!! Form::label('month_salary_gross', 'Salario bruto mensual', ['class' => 'required']) !!}
                    {!! Form::text('month_salary_gross', $postulant->month_salary_gross, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario bruto mensual']) !!}
                    @error('month_salary_gross')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>

            <div class="row form-group">

                <div class="col-sm ">
                    {!! Form::label('month_salary_net', 'Salario neto mensual', ['class' => 'required']) !!}
                    {!! Form::text('month_salary_net', $postulant->month_salary_net, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario neto mensual']) !!}
                    @error('month_salary_net')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
                <div class="col-sm ">
                    {!! Form::label('daily_salary', 'Salario diario', ['class' => 'required']) !!}
                    {!! Form::text('daily_salary', $postulant->daily_salary, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario diario']) !!}
                    @error('daily_salary')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div> 

                <div class="col-sm ">
                    {!! Form::label('daily_salary_letter', 'Salario diario en letra', ['class' => 'required']) !!}
                    {!! Form::text('daily_salary_letter', $postulant->daily_salary_letter, ['class' => 'form-control', 'placeholder' => 'Ingrese el salario diario en letra']) !!}
                    @error('daily_salary_letter')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>
            <br><br>

            <h5>Detalles del Puesto</h5>
            <div class="row form-group">
                
                <div class="col-sm ">
                    {!! Form::label('company_id', 'Empresa de interés', ['class' => 'required']) !!}
                    {!! Form::select('company_id', $companies, $postulant->company_id, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                    @error('company_id')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>  

                <div class="col-sm ">
                    {!! Form::label('department_id', 'Área o departamento de vacante', ['class' => 'required']) !!}
                    {!! Form::select('department_id', $departments, $postulant->department_id, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                    @error('company_id')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>  

                <div class="col-sm ">
                   {!! Form::label('vacant', 'Vacante', ['class' => 'required']) !!}
                   {!! Form::text('vacant', $postulant->vacant, ['class' => 'form-control', 'placeholder' => 'Ingrese el objetivo del puesto']) !!}
                   @error('vacant')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>       

           </div>

           <div class="row form-group">
                <div class="col-sm">
                   {!! Form::label('position_objetive', 'Objetivo del vacante', ['class' => 'required']) !!}
                   {!! Form::text('position_objetive', $postulant->position_objetive, ['class' => 'form-control', 'placeholder' => 'Ingrese el objetivo del puesto']) !!}
                   @error('position_objetive')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div> 

                <div class="col-sm"></div>

                <div class="col-sm"></div>
           </div>
            <br><br>

            <h5>Información Bancaria (opcional)</h5>

            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('bank_name', 'Banco') !!}
                    {!! Form::text('bank_name',  $postulant->bank_name, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del banco']) !!}
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('card_number', 'N° tarjeta/cuenta') !!}
                    {!! Form::text('card_number', $postulant->card_number, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de cuenta']) !!}
                </div>
                
            </div>

           
            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('infonavit_credit', 'Credito infonavit') !!}
                    {!! Form::select('infonavit_credit', ['si' => 'Si', 'no' => 'No'], $postulant->infonavit_credit, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('factor_credit_number', 'N° credito factor') !!}
                    {!! Form::text('factor_credit_number', $postulant->factor_credit_number, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito factor']) !!}
                </div>
                
            </div>

            <div class="row form-group">
                <div class="col-sm col-md-4">
                    {!! Form::label('fonacot_credit', '¿Crédito fonacot?') !!}
                    {!! Form::select('fonacot_credit', ['si' => 'Si', 'no' => 'No'], $postulant->fonacot_credit, ['class' => 'form-control','placeholder' => 'Seleccionar']) !!}
                </div>

                <div class="col-sm col-md-8">
                    {!! Form::label('discount_credit_number', 'N° credito descuento') !!}
                    {!! Form::text('discount_credit_number',  $postulant->discount_credit_number, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de credito descuento']) !!}
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
        .required:after {
            content:" *";
            color: red;
        }
   </style>
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function wrongAlert() {
            Swal.fire('No disponible hasta completar "Recepción de Documentos"');
        }
        
    </script>
@stop