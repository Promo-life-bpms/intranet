@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Organigrama</h3>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">General</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">Especifico</button>
            </li>
        </ul>
        <div class="tab-content mx-2" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div id="tree"></div>
            </div>
            <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div id="tree-especifico"></div>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Empresas</h5>
                        @foreach ($organizations as $org)
                            <input class="checksEspecificos org" type="checkbox" name="{{ trim($org->name_company) }}"
                                id="" value="{{ $org->id }}">
                            {{ $org->name_company }}
                            <br>
                        @endforeach
                    </div>
                    <div class="col-md-6">
                        <h5>Departamentos</h5>
                        @foreach ($departments as $department)
                            <input class="checksEspecificos position" type="checkbox" name="{{ trim($department->name) }}"
                                id="" value="{{ $department->id }}">
                            {{ $department->name }}
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="{{ asset('assets\vendors\orgchartjs\orgchart.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select[name="department"]').on('change', function() {
                var id = jQuery(this).val();
                if (id) {
                    jQuery.ajax({
                        url: '/company/getPosition/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            jQuery('ul[name="position"]').empty();
                            jQuery.each(data, function(key, value) {
                                $('ul[name="position"]').append('<li>' + value +
                                    '</li>');
                            });
                        }
                    });
                } else {
                    $('select[name="position"]').empty();
                }
            });
        });
        obtenerEmpleados()

        async function obtenerEmpleados() {
            try {
                let res = await axios.get("/company/getEmployees");
                let data = res.data;
                var chart = new OrgChart(document.getElementById("tree"), {
                    template: "ula",
                    nodeBinding: {
                        field_0: "Nombre",
                        field_1: "Puesto",
                        img_0: "Photo"
                    },
                    editForm: {
                        buttons: {
                            pdf: null,
                            share: null,
                            edit: null
                        }
                    }
                });

                nodes = data;

                chart.on('init', function(sender) {
                    sender.editUI.show(1);
                });

                chart.load(nodes);
            } catch {
                console.log(error);
            }
        }

        const checksEspecificos = document.querySelectorAll('.checksEspecificos')
        checksEspecificos.forEach(check => {
            check.addEventListener('change', () => {
                crearOrganigrama(check)
            })
        });

        async function crearOrganigrama(check) {
            checksEspecificos.forEach(ch => {
                ch.checked = false
            });
            check.checked = true
            let url = check.classList.contains('org') ?
                "/company/getEmployeesByOrganization" :
                "/company/getEmployeesByDepartment";
            try {
                let res = await axios.get(`${url}/${check.value}`);
                let data = res.data;
                if (data.length > 0) {
                    var chart = new OrgChart(document.getElementById("tree-especifico"), {
                        template: "ula",
                        nodeBinding: {
                            field_0: "Nombre",
                            field_1: "Puesto",
                            img_0: "Photo"
                        },
                        editForm: {
                            buttons: {
                                pdf: null,
                                share: null,
                                edit: null
                            }
                        }
                    });

                    nodes = data;

                    chart.on('init', function(sender) {
                        sender.editUI.show(1);
                    });

                    chart.load(nodes);
                }
            } catch {
                console.log(error);
            }
        }
    </script>
@stop
