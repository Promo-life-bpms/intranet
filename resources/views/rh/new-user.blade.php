@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3 class="separator">Generar alta</h3> 
    </div>
    <div class="card-body">
    <div class="d-flex flex-row justify-content-between add-container" >
        <h6>Lista de postulantes disponibles</h6>
        <a class="btn btn-success" href="{{ route('rh.createPostulant') }}">
            <i style="margin-right:8px" class="fa fa-plus" aria-hidden="true"></i>
            Agregar nuevo
        </a>
    </div>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
       
    <div class="table-responsive">
            <table class="table table-striped" id="table-directory">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col" class="text-center">Información de contacto</th>
                        <th scope="col" class="text-center">Empresa de interés</th>
                        <th scope="col">Departamento de interes</th>
                        <th scope="col">Fecha de entrevista</th>
                        <th scope="col">Status</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($postulants_data as $postulant)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td> <b>{{ $postulant->fullname }}</b></td>
                            <td class="text-center"> 
                                {{ $postulant->mail}}
                                <br>
                                {{ $postulant->phone}}
                            </td>
                            <td class="text-center">
                                @if ($postulant->company <> null)
                                    {{ $postulant->company }}
                                @endif
                            </td>
                            <td>
                                @if ($postulant->department <> null)
                                    {{ $postulant->department }}
                                @endif
                            </td>
                            <td>
                                @if ($postulant->interview_date <> null)
                                    {{ $postulant->interview_date }}
                                @endif
                            </td>
                            <td><b>{{ $postulant->status }}</b> </td>
                            <td>
                                <div class="d-flex w-100 ">
                                    <div>
                                       
                                    </div>
                                </div>
                                <div class="d-flex" >
                                    ¿
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
   
    </div>
@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-directory').DataTable({
                language: {
                    "aria": {
                        "sortAscending": "Activar para ordenar la columna de manera ascendente",
                        "sortDescending": "Activar para ordenar la columna de manera descendente"
                    },
                    "autoFill": {
                        "cancel": "Cancelar",
                        "fill": "Rellene todas las celdas con <i>%d<\/i>",
                        "fillHorizontal": "Rellenar celdas horizontalmente",
                        "fillVertical": "Rellenar celdas verticalmente"
                    },
                    "buttons": {
                        "collection": "Colección",
                        "colvis": "Visibilidad",
                        "colvisRestore": "Restaurar visibilidad",
                        "copy": "Copiar",
                        "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                        "copySuccess": {
                            "1": "Copiada 1 fila al portapapeles",
                            "_": "Copiadas %d fila al portapapeles"
                        },
                        "copyTitle": "Copiar al portapapeles",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Mostrar todas las filas",
                            "_": "Mostrar %d filas"
                        },
                        "pdf": "PDF",
                        "print": "Imprimir",
                        "createState": "Crear Estado",
                        "removeAllStates": "Borrar Todos los Estados",
                        "removeState": "Borrar Estado",
                        "renameState": "Renombrar Estado",
                        "savedStates": "Guardar Estado",
                        "stateRestore": "Restaurar Estado",
                        "updateState": "Actualizar Estado"
                    },
                    "infoThousands": ",",
                    "loadingRecords": "Cargando...",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "searchBuilder": {
                        "add": "Añadir condición",
                        "button": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "clearAll": "Borrar todo",
                        "condition": "Condición",
                        "deleteTitle": "Eliminar regla de filtrado",
                        "leftTitle": "Criterios anulados",
                        "logicAnd": "Y",
                        "logicOr": "O",
                        "rightTitle": "Criterios de sangría",
                        "title": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "value": "Valor",
                        "conditions": {
                            "date": {
                                "after": "Después",
                                "before": "Antes",
                                "between": "Entre",
                                "empty": "Vacío",
                                "equals": "Igual a",
                                "not": "Diferente de",
                                "notBetween": "No entre",
                                "notEmpty": "No vacío"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vacío",
                                "equals": "Igual a",
                                "gt": "Mayor a",
                                "gte": "Mayor o igual a",
                                "lt": "Menor que",
                                "lte": "Menor o igual a",
                                "not": "Diferente de",
                                "notBetween": "No entre",
                                "notEmpty": "No vacío"
                            },
                            "string": {
                                "contains": "Contiene",
                                "empty": "Vacío",
                                "endsWith": "Termina con",
                                "equals": "Igual a",
                                "not": "Diferente de",
                                "startsWith": "Inicia con",
                                "notEmpty": "No vacío",
                                "notContains": "No Contiene",
                                "notEnds": "No Termina",
                                "notStarts": "No Comienza"
                            },
                            "array": {
                                "equals": "Igual a",
                                "empty": "Vacío",
                                "contains": "Contiene",
                                "not": "Diferente",
                                "notEmpty": "No vacío",
                                "without": "Sin"
                            }
                        },
                        "data": "Datos"
                    },
                    "searchPanes": {
                        "clearMessage": "Borrar todo",
                        "collapse": {
                            "0": "Paneles de búsqueda",
                            "_": "Paneles de búsqueda (%d)"
                        },
                        "count": "{total}",
                        "emptyPanes": "Sin paneles de búsqueda",
                        "loadMessage": "Cargando paneles de búsqueda",
                        "title": "Filtros Activos - %d",
                        "countFiltered": "{shown} ({total})",
                        "collapseMessage": "Colapsar",
                        "showMessage": "Mostrar Todo"
                    },
                    "select": {
                        "cells": {
                            "1": "1 celda seleccionada",
                            "_": "%d celdas seleccionadas"
                        },
                        "columns": {
                            "1": "1 columna seleccionada",
                            "_": "%d columnas seleccionadas"
                        }
                    },
                    "thousands": ",",
                    "datetime": {
                        "previous": "Anterior",
                        "hours": "Horas",
                        "minutes": "Minutos",
                        "seconds": "Segundos",
                        "unknown": "-",
                        "amPm": [
                            "am",
                            "pm"
                        ],
                        "next": "Siguiente",
                        "months": {
                            "0": "Enero",
                            "1": "Febrero",
                            "10": "Noviembre",
                            "11": "Diciembre",
                            "2": "Marzo",
                            "3": "Abril",
                            "4": "Mayo",
                            "5": "Junio",
                            "6": "Julio",
                            "7": "Agosto",
                            "8": "Septiembre",
                            "9": "Octubre"
                        },
                        "weekdays": [
                            "Domingo",
                            "Lunes",
                            "Martes",
                            "Miércoles",
                            "Jueves",
                            "Viernes",
                            "Sábado"
                        ]
                    },
                    "editor": {
                        "close": "Cerrar",
                        "create": {
                            "button": "Nuevo",
                            "title": "Crear Nuevo Registro",
                            "submit": "Crear"
                        },
                        "edit": {
                            "button": "Editar",
                            "title": "Editar Registro",
                            "submit": "Actualizar"
                        },
                        "remove": {
                            "button": "Eliminar",
                            "title": "Eliminar Registro",
                            "submit": "Eliminar",
                            "confirm": {
                                "_": "¿Está seguro que desea eliminar %d filas?",
                                "1": "¿Está seguro que desea eliminar 1 fila?"
                            }
                        },
                        "multi": {
                            "title": "Múltiples Valores",
                            "restore": "Deshacer Cambios",
                            "noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo.",
                            "info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, haga click o toque aquí, de lo contrario conservarán sus valores individuales."
                        },
                        "error": {
                            "system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\"> Más información<\/a>)."
                        }
                    },
                    "decimal": ".",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "zeroRecords": "No se encontraron coincidencias",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ ",
                    "infoEmpty": "Mostrando 0 a 0 de 0 ",
                    "infoFiltered": "(Filtrado de _MAX_ total de )",
                    "lengthMenu": "Mostrar _MENU_ ",
                    "stateRestore": {
                        "removeTitle": "Eliminar",
                        "creationModal": {
                            "search": "Busccar"
                        }
                    }
                }
            });

        });
    </script>

@endsection


@section('styles')

    <style>
        .add-container{
            margin-bottom: 20px;
        }

        .btn-option{
            width: 145px;
            background-color: #006EAD; 
            margin-bottom: 5px;
            color:white;
        }

        .btn-option2{
            width: 145px;
            background-color: #D83A4C;
            color: white;
        }

        .btn-option:hover{
            background-color: #045280; 
            color:white;
        }

        .btn-option2:hover{
           
            background-color: #a82d3b;
            color: white;
        }
    </style>
    <style>
        table.dataTable {
            width: 100%;
            margin: 0 auto;
            clear: both;
            border-collapse: separate;
            border-spacing: 0;
        }

        table.dataTable thead th,
        table.dataTable tfoot th {
            font-weight: bold;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 10px 18px;
            border-bottom: 1px solid #ebebeb;
        }

        table.dataTable thead th:active,
        table.dataTable thead td:active {
            outline: none;
        }

        table.dataTable tfoot th,
        table.dataTable tfoot td {
            padding: 10px 18px 6px 18px;
            border-top: 1px solid #ebebeb;
        }

        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc,
        table.dataTable thead .sorting_asc_disabled,
        table.dataTable thead .sorting_desc_disabled {
            cursor: pointer;
            background-repeat: no-repeat;
            background-position: center right;
        }

        table.dataTable tbody tr {
            background-color: #ffffff;
        }

        table.dataTable tbody tr.selected {
            background-color: #b0bed9;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 8px 10px;
        }

        table.dataTable.row-border tbody th,
        table.dataTable.row-border tbody td,
        table.dataTable.display tbody th,
        table.dataTable.display tbody td {
            border-top: 1px solid #dddddd;
        }

        table.dataTable.row-border tbody tr:first-child th,
        table.dataTable.row-border tbody tr:first-child td,
        table.dataTable.display tbody tr:first-child th,
        table.dataTable.display tbody tr:first-child td {
            border-top: none;
        }

        table.dataTable.cell-border tbody th,
        table.dataTable.cell-border tbody td {
            border-top: 1px solid #dddddd;
            border-right: 1px solid #dddddd;
        }

        table.dataTable.cell-border tbody tr th:first-child,
        table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid #dddddd;
        }

        table.dataTable.cell-border tbody tr:first-child th,
        table.dataTable.cell-border tbody tr:first-child td {
            border-top: none;
        }

        table.dataTable.stripe tbody tr.odd,
        table.dataTable.display tbody tr.odd {
            background-color: #f9f9f9;
        }

        table.dataTable.stripe tbody tr.odd.selected,
        table.dataTable.display tbody tr.odd.selected {
            background-color: #acbad4;
        }

        table.dataTable.hover tbody tr:hover,
        table.dataTable.display tbody tr:hover {
            background-color: #f6f6f6;
        }

        table.dataTable.hover tbody tr:hover.selected,
        table.dataTable.display tbody tr:hover.selected {
            background-color: #aab7d1;
        }

        table.dataTable.order-column tbody tr>.sorting_1,
        table.dataTable.order-column tbody tr>.sorting_2,
        table.dataTable.order-column tbody tr>.sorting_3,
        table.dataTable.display tbody tr>.sorting_1,
        table.dataTable.display tbody tr>.sorting_2,
        table.dataTable.display tbody tr>.sorting_3 {
            background-color: #fafafa;
        }

        table.dataTable.order-column tbody tr.selected>.sorting_1,
        table.dataTable.order-column tbody tr.selected>.sorting_2,
        table.dataTable.order-column tbody tr.selected>.sorting_3,
        table.dataTable.display tbody tr.selected>.sorting_1,
        table.dataTable.display tbody tr.selected>.sorting_2,
        table.dataTable.display tbody tr.selected>.sorting_3 {
            background-color: #acbad5;
        }

        table.dataTable.display tbody tr.odd>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_1 {
            background-color: #f1f1f1;
        }

        table.dataTable.display tbody tr.odd>.sorting_2,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_2 {
            background-color: #f3f3f3;
        }

        table.dataTable.display tbody tr.odd>.sorting_3,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_3 {
            background-color: whitesmoke;
        }

        table.dataTable.display tbody tr.odd.selected>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_1 {
            background-color: #a6b4cd;
        }

        table.dataTable.display tbody tr.odd.selected>.sorting_2,
        table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_2 {
            background-color: #a8b5cf;
        }

        table.dataTable.display tbody tr.odd.selected>.sorting_3,
        table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_3 {
            background-color: #a9b7d1;
        }

        table.dataTable.display tbody tr.even>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_1 {
            background-color: #fafafa;
        }

        table.dataTable.display tbody tr.even>.sorting_2,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_2 {
            background-color: #fcfcfc;
        }

        table.dataTable.display tbody tr.even>.sorting_3,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_3 {
            background-color: #fefefe;
        }

        table.dataTable.display tbody tr.even.selected>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_1 {
            background-color: #acbad5;
        }

        table.dataTable.display tbody tr.even.selected>.sorting_2,
        table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_2 {
            background-color: #aebcd6;
        }

        table.dataTable.display tbody tr.even.selected>.sorting_3,
        table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_3 {
            background-color: #afbdd8;
        }

        table.dataTable.display tbody tr:hover>.sorting_1,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_1 {
            background-color: #eaeaea;
        }

        table.dataTable.display tbody tr:hover>.sorting_2,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_2 {
            background-color: #ececec;
        }

        table.dataTable.display tbody tr:hover>.sorting_3,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_3 {
            background-color: #efefef;
        }

        table.dataTable.display tbody tr:hover.selected>.sorting_1,
        table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_1 {
            background-color: #a2aec7;
        }

        table.dataTable.display tbody tr:hover.selected>.sorting_2,
        table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_2 {
            background-color: #a3b0c9;
        }

        table.dataTable.display tbody tr:hover.selected>.sorting_3,
        table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_3 {
            background-color: #a5b2cb;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #ebebeb;
        }

        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: nowrap;
        }

        table.dataTable.compact thead th,
        table.dataTable.compact thead td {
            padding: 4px 17px;
        }

        table.dataTable.compact tfoot th,
        table.dataTable.compact tfoot td {
            padding: 4px;
        }

        table.dataTable.compact tbody th,
        table.dataTable.compact tbody td {
            padding: 4px;
        }

        table.dataTable th,
        table.dataTable td {
            box-sizing: content-box;
        }

        .dataTables_wrapper {
            position: relative;
            clear: both;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            padding: 4px;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            margin-left: 3px;
        }

        .dataTables_wrapper .dataTables_info {
            clear: both;
            float: left;
            padding-top: 0.755em;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            text-align: right;
            padding-top: 0.25em;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box;
            display: inline-block;
            min-width: 1.5em;
            padding: 0.5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            *cursor: hand;
            color: #000000 !important;
            border: 1px solid transparent;
            border-radius: 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: #FFFFFF !important;
            background-color: #006EAD;
            border-radius: 5px;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            cursor: default;
            color: #666 !important;
            border: 1px solid transparent;
            background: transparent;
            box-shadow: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: rgb(0, 0, 0) !important;
            border: 1px solid #ffffff;
            background-color: white;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:active {
            outline: none;
            background-color: white;
            box-shadow: inset 0 0 3px #111;
        }

        .dataTables_wrapper .dataTables_paginate .ellipsis {
            padding: 0 1em;
        }

        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 40px;
            margin-left: -50%;
            margin-top: -25px;
            padding-top: 20px;
            text-align: center;
            font-size: 1.2em;
            background-color: white;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: #000000;
        }

        .dataTables_wrapper .dataTables_scroll {
            clear: both;
        }

        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody {
            *margin-top: -1px;
            -webkit-overflow-scrolling: touch;
        }

        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>th,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>td,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>th,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>td {
            vertical-align: middle;
        }

        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>th>div.dataTables_sizing,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>td>div.dataTables_sizing,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>th>div.dataTables_sizing,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>td>div.dataTables_sizing {
            height: 0;
            overflow: hidden;
            margin: 0 !important;
            padding: 0 !important;
        }

        .dataTables_wrapper.no-footer .dataTables_scrollBody {
            border-bottom: 1px solid #ebebeb;
        }

        .dataTables_wrapper.no-footer div.dataTables_scrollHead table.dataTable,
        .dataTables_wrapper.no-footer div.dataTables_scrollBody>table {
            border-bottom: none;
        }

        .dataTables_wrapper:after {
            visibility: hidden;
            display: block;
            content: "";
            clear: both;
            height: 0;
        }
    </style>
@endsection