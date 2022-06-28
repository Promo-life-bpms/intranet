@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/orgchart/3.1.1/css/jquery.orgchart.min.css"
        integrity="sha512-bCaZ8dJsDR+slK3QXmhjnPDREpFaClf3mihutFGH+RxkAcquLyd9iwewxWQuWuP5rumVRl7iGbSDuiTvjH1kLw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/orgchart/3.1.1/js/jquery.orgchart.min.js"
        integrity="sha512-alnBKIRc2t6LkXj07dy2CLCByKoMYf2eQ5hLpDmjoqO44d3JF8LSM4PptrgvohTQT0LzKdRasI/wgLN0ONNgmA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $(function() {

            var datascource = {
                'name': 'Lao Lao',
                'title': 'general manager',
                'children': [{
                        'name': 'Bo Miao',
                        'title': 'department manager'
                    },
                    {
                        'name': 'Su Miao',
                        'title': 'department manager',
                        'children': [{
                                'name': 'Tie Hua',
                                'title': 'senior engineer'
                            },
                            {
                                'name': 'Hei Hei',
                                'title': 'senior engineer',
                                'children': [{
                                        'name': 'Pang Pang',
                                        'title': 'engineer'
                                    },
                                    {
                                        'name': 'Dan Dan',
                                        'title': 'UE engineer',
                                        'children': [{
                                                'name': 'Er Dan',
                                                'title': 'engineer'
                                            },
                                            {
                                                'name': 'San Dan',
                                                'title': 'engineer',
                                                'children': [{
                                                        'name': 'Si Dan',
                                                        'title': 'intern'
                                                    },
                                                    {
                                                        'name': 'Wu Dan',
                                                        'title': 'intern'
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        'name': 'Hong Miao',
                        'title': 'department manager'
                    },
                    {
                        'name': 'Chun Miao',
                        'title': 'department manager',
                        'children': [{
                                'name': 'Bing Qin',
                                'title': 'senior engineer'
                            },
                            {
                                'name': 'Yue Yue',
                                'title': 'senior engineer',
                                'children': [{
                                        'name': 'Er Yue',
                                        'title': 'engineer'
                                    },
                                    {
                                        'name': 'San Yue',
                                        'title': 'UE engineer'
                                    }
                                ]
                            }
                        ]
                    }
                ]
            };
            var oc = $('#chart-container').orgchart({
                'data': datascource,
                'nodeContent': 'title',
            });

            // $('#home').resize(function() {
            //     var width = $('#home').width();
            //     console.log(width);
            //     if (width > 576) {
            //         oc.init({
            //             'verticalLevel': undefined
            //         });
            //     } else {
            //         oc.init({
            //             'verticalLevel': 2
            //         });
            //     }
            // });

        });
    </script>
@endsection
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
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">Especifico</button>
            </li> --}}
        </ul>
        <div class="tab-content mx-2" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div id="chart-container"></div>
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

{{-- @section('styles')
    <style>
        .edit-form-avatar img {
            object-fit: cover;

        }
    </style>

@endsection

@section('scrispts')
    <script src="{{ asset('assets\vendors\orgchartjs\orgchart.js') }}"></script>
    <script>
        async function obtenerEmpleados(check) {
            let res = await axios.get("/company/getEmployees");
            let data = res.data;
            crearOrganigrama(data)
        }

        obtenerEmpleados()

        function crearOrganigrama(employees) {
            console.log(employees);
            var chart = new OrgChart(document.getElementById("tree"), {
                enableSearch: false,
                enableDragDrop: true,
                mouseScrool: OrgChart.none,
                toolbar: {
                    fullScreen: true,
                    zoom: true,
                    fit: true,
                    expandAll: true
                },
                tags: {
                    "assistant": {
                        template: "ula"
                    }
                },
                nodeMenu: {
                    details: {
                        text: "Details"
                    },
                    edit: {
                        text: "Edit"
                    },
                    add: {
                        text: "Add"
                    },
                    remove: {
                        text: "Remove"
                    }
                },
                nodeBinding: {
                    field_0: "name",
                    field_1: "title",
                    img_0: "img"
                }
            });

            chart.load(employees);
        }
    </script>
@endsection

@section('styles')
    <style>
        /*CSS*/
        html,
        body {
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
            font-family: Helvetica;
            overflow: hidden;
        }

        #tree {
            width: 100%;
            height: 100%;
        }
    </style>
@endsection


@section('script')
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
                    showXScroll: OrgChart.scroll.visible,
                    mouseScrool: OrgChart.action.xScroll,
                    scaleInitial: OrgChart.match.height,
                    scaleInitial: 0.8,
                    lazyLoading: true,
                    template: "ula",
                    nodeBinding: {
                        field_0: "Nombre",
                        field_1: "Puesto",
                        img_0: "Photo"
                    },
                    editForm: {
                        titleBinding: "Nombre",
                        photoBinding: "Photo",
                        generateElementsFromFields: false,
                        buttons: {
                            pdf: null,
                            share: null,
                            edit: null,
                        },
                        elements: [{
                                type: 'textbox',
                                label: 'Nombre',
                                binding: 'Nombre'
                            },
                            {
                                type: 'textbox',
                                label: 'Puesto',
                                binding: 'Puesto',
                            },
                        ],
                    }
                });

                nodes = data;

                /* chart.on('init', function(sender) {
                      sender.editUI.show(2);
                  }); */

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
                        showXScroll: OrgChart.scroll.visible,
                        mouseScrool: OrgChart.action.xScroll,
                        scaleInitial: OrgChart.match.height,
                        scaleInitial: 0.8,
                        lazyLoading: true,
                        template: "ula",
                        nodeBinding: {
                            field_0: "Nombre",
                            field_1: "Puesto",
                            img_0: "Photo"
                        },
                        editForm: {
                            titleBinding: "Nombre",
                            photoBinding: "Photo",
                            generateElementsFromFields: false,
                            buttons: {
                                pdf: null,
                                share: null,
                                edit: null,
                            },
                            elements: [{
                                    type: 'textbox',
                                    label: 'Nombre',
                                    binding: 'Nombre'
                                },
                                {
                                    type: 'textbox',
                                    label: 'Puesto',
                                    binding: 'Puesto',
                                },
                            ],
                        }
                    });

                    nodes = data;

                    /*  chart.on('init', function(sender) {
                         sender.editUI.show(1);
                     }); */

                    chart.load(nodes);
                }
            } catch {
                console.log(error);
            }
        }
    </script>
@stop --}}
