<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('nombre') }}
            {{ Form::text('name', $provider->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Nombre del proveedor']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Palabra Clave') }}
            {{ Form::text('service', $provider->service, ['class' => 'form-control' . ($errors->has('service') ? ' is-invalid' : ''), 'placeholder' => 'Palabra Clave del producto o servicio']) }}
            {!! $errors->first('service', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Tipo de Producto o Servicio') }}
            {{ Form::text('type', $provider->type, ['class' => 'form-control' . ($errors->has('type') ? ' is-invalid' : ''), 'placeholder' => 'Tipo de Producto o servicio']) }}
            {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Contacto') }}
            {{ Form::text('name_contact', $provider->name_contact, ['class' => 'form-control' . ($errors->has('name_contact') ? ' is-invalid' : ''), 'placeholder' => 'Nombre del Contacto']) }}
            {!! $errors->first('name_contact', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Puesto') }}
            {{ Form::text('position', $provider->position, ['class' => 'form-control' . ($errors->has('position') ? ' is-invalid' : ''), 'placeholder' => 'Puesto del Contacto']) }}
            {!! $errors->first('position', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Telefono de Oficina') }}
            {{ Form::text('tel_office', $provider->tel_office, ['class' => 'form-control' . ($errors->has('tel_office') ? ' is-invalid' : ''), 'placeholder' => 'Telefono de Oficina']) }}
            {!! $errors->first('tel_office', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Celular') }}
            {{ Form::text('tel_cel', $provider->tel_cel, ['class' => 'form-control' . ($errors->has('tel_cel') ? ' is-invalid' : ''), 'placeholder' => 'Celular']) }}
            {!! $errors->first('tel_cel', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Email') }}
            {{ Form::text('email', $provider->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Correo electronico']) }}
            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Direccion') }}
            {{ Form::text('address', $provider->address, ['class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Direccion']) }}
            {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Pagina web') }}
            {{ Form::text('web_page', $provider->web_page, ['class' => 'form-control' . ($errors->has('web_page') ? ' is-invalid' : ''), 'placeholder' => 'Pagina web del proveedor']) }}
            {!! $errors->first('web_page', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </div>
</div>