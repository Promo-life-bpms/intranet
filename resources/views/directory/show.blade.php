@extends('layouts.app')

@section('content')
    <div class="card-header">
      
        <div class="d-flex justify-content-between">

            @foreach ($user as $user)
                <h3>{{ $user->name . ' ' . $user->lastname }}</h3>
            @endforeach

            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImage">
            Agregar
            </button>
        </div>
    </div>
    <div class="card-body">
        
        <div class="row">
            @foreach ($directory as $directory)
            {!! Form::model($directory, ['route' => ['directories.update', $directory], 'method' => 'put', 'enctype' => 'multipart/form-data']) !!}

                <div class="card bg-light p-4">
                    <div class="row">
                        <div class="col-md-2">
                            {!! Form::label('type', 'Tipo') !!}
                            {!! Form::select('type', ['Email' => 'Email', 'Telefono' => 'Telefono'],null, ['class' => 'form-control', 'placeholder' => 'Tipo']) !!}
                            
                            @error('type')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror
                        
                        </div>
        
                        <div class="col-md-4">
                            {!! Form::label('data', 'Telefono/Correo') !!}
                            {!! Form::text('data', $directory->data, ['class' => 'form-control', 'placeholder' => 'Tipo']) !!}
                            
                            @error('data')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror
                        
                        </div>
        
                        <div class="col-md-2">
                            {!! Form::label('company', 'Empresa') !!}
                            {!! Form::select('company', $companies, $directory->company, ['class' => 'form-control', 'placeholder' => 'Tipo']) !!}
                            
                            @error('company')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror
                        

                        </div>

                        <div class="col md-2">
                            {!! Form::label('options', 'Opcion') !!}
                            {!! Form::submit('ACTUALIZAR ', ['class' => 'btn btn-primary form-control']) !!}
                        </div>

                    {!! Form::close() !!}

                        <div class="col md-2">
                            {!! Form::label('options', 'Opcion') !!}
                                <form 
                                action="{{ route('directories.destroy', ['directory' => $directory->id]) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button style="width: 100%" type="submit" class="btn btn-danger">
                                    BORRAR
                                </button>
                                </form>
                        </div>
                    </div>

                </div>

            @endforeach

        </div> 

        <div class="modal fade" id="modalImage" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ingrese los datos</h5>
                    </div>

                    {!! Form::open(['route' => 'directories.store']) !!}
                    
                    <div class="modal-body">
                        <div class="row">

                            {!! Form::text('user_id',$user->id, ['class' => 'form-control','hidden']) !!}
                            
                            <div class="col-md-6">
                                {!! Form::label('type', 'Tipo') !!}
                                {!! Form::select('type', ['Email' => 'Email', 'Telefono' => 'Telefono'],null, ['class' => 'form-control', 'placeholder' => 'Tipo']) !!}

                                @error('type')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                    <br>
                                @enderror
                                
                            </div>
            
                            <div class="col-md-6">
                                {!! Form::label('company', 'Empresa') !!}
                                {!! Form::select('company', $companies, null, ['class' => 'form-control', 'placeholder' => 'Tipo']) !!}

                                @error('company')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                    <br>
                                @enderror

                            </div>
                            
                           
                            <div class="col-md-12 mt-4">
                                {!! Form::label('data', 'Telefono/Correo') !!}
                                {!! Form::text('data', null, ['class' => 'form-control', 'placeholder' => 'Tipo']) !!}
                                
                                @error('data')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                    <br>
                                @enderror
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        {!! Form::submit('Aceptar', ['class' => 'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
    </div>
@stop

@section('scripts')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('.form-delete').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El registro se eliminará permanentemente!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
@stop
