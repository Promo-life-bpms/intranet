<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group ">
            {{ Form::label('Empleado') }}
            <select name="user_id" class="form-control employee">
                <option value="">Seleccione...</option>
                @foreach ($list as $item)
                    <option {{ $item->id == $directory->user_id ? 'selected' : '' }} value="{{ $item->id }}">
                        {{ $item->name }}</option>
                @endforeach
            </select>
            @error('user_id')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
            @enderror
            {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Tipo') }}
            <select name="type" class="form-control">
                <option value="">Seleccione...</option>
                <option {{ 'Email' == $directory->type ? 'selected' : '' }} value="Email">Email</option>
                <option {{ 'Telefono' == $directory->type ? 'selected' : '' }} value="Telefono">Telefono</option>
            </select>
            @error('type')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
            @enderror

            {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('correo/telefono') }}
            {{ Form::text('data', $directory->data, ['class' => 'form-control' . ($errors->has('data') ? ' is-invalid' : ''),'placeholder' => 'Correo o Telefono']) }}
            @error('data')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
            @enderror
    
            {!! $errors->first('data', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('company') }}
            <select name="company" class="form-control company">
                <option value="">Seleccione...</option>
                @foreach ($listCompany as $item)
                    <option {{ $item->id == $directory->company ? 'selected' : '' }} value="{{ $item->id }}">
                        {{ $item->name_company }}</option>
                @endforeach
            </select>
            @error('company')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
            @enderror
            {!! $errors->first('company', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </div>
</div>
