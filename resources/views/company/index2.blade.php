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
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">Especifico</button>
            </li> --}}
        </ul>
        <div class="tab-content mx-2" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="org">
                    <div class="organigrama">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('styles')
    <style>
        .org {

            overflow-x: scroll;
            text-align: center;
        }

        .organigrama {
            text-align: center;
        }

        .organigrama * {
            margin: 0px;
            padding: 0px;
        }

        .organigrama ul {
            padding-top: 20px;
            position: relative;
            display: flex;
        }

        .organigrama li {
            float: left;
            text-align: center;
            list-style-type: none;
            padding: 20px 5px 0px 5px;
            position: relative;
        }

        .organigrama li::before,
        .organigrama li::after {
            content: '';
            position: absolute;
            top: 0px;
            right: 50%;
            border-top: 1px solid rgb(21, 33, 192);
            width: 50%;
            height: 20px;
        }

        .organigrama li::after {
            right: auto;
            left: 50%;
            border-left: 1px solid rgb(21, 33, 192);
        }

        .organigrama li:only-child::before,
        .organigrama li:only-child::after {
            display: none;
        }

        .organigrama li:only-child {
            padding-top: 0;
        }

        .organigrama li:first-child::before,
        .organigrama li:last-child::after {
            border: 0 none;
        }

        .organigrama li:last-child::before {
            border-right: 1px solid rgb(21, 33, 192);
            -webkit-border-radius: 0 5px 0 0;
            -moz-border-radius: 0 5px 0 0;
            border-radius: 0 5px 0 0;
        }

        .organigrama li:first-child::after {
            border-radius: 5px 0 0 0;
            -webkit-border-radius: 5px 0 0 0;
            -moz-border-radius: 5px 0 0 0;
        }

        .organigrama ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 1px solid rgb(21, 33, 192);
            width: 0;
            height: 20px;
        }

        .organigrama li a {
            border: 1px solid rgb(21, 33, 192);
            padding: 1em 0.75em;
            text-decoration: none;
            color: #333;
            background-color: rgba(255, 255, 255, 0.5);
            font-family: arial, verdana, tahoma;
            font-size: 0.85em;
            display: inline-block;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-transition: all 500ms;
            -moz-transition: all 500ms;
            transition: all 500ms;
        }

        .organigrama li a:hover {
            border: 1px solid #fff;
            color: #ddd;
            background-color: rgba(21, 33, 192, 0.574);
            display: inline-block;
        }

        .organigrama>ul>li>a {
            font-size: 1em;
            font-weight: bold;
        }

        .contenido-user {
            width: 240px;
            background-color: #039BE5 !important;
        }

        .contenido-user p {
            color: white;

        }

        .img-photo img {
            width: 90px;
            margin-top: -35px;
            margin-left: -5px;
            margin-bottom: 5px;
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/company/getEmployees')
                .then(function(response) {
                    return response.json();
                })
                .then(function(response) {
                    response.sort(function(a, b) {
                        if (a.pid > b.pid) {
                            return 1;
                        }
                        if (a.pid < b.pid) {
                            return -1;
                        }
                        // a must be equal to b
                        return 0;
                    });
                    console.log(response);
                    addNodos(response, [])
                });

            function addNodos(response, paramdataOrg) {
                const org = document.querySelector('.organigrama')
                let dataOrg = paramdataOrg;
                let faltantes = []
                for (let i = 0; i < response.length; i++) {
                    const person = response[i];
                    let elementParent = null;
                    let beforeElement = null;
                    for (let j = 0; j < dataOrg.length; j++) {
                        const elementData = dataOrg[j];
                        if (elementData.id == person.pid) {
                            elementParent = elementData
                        }
                        if (elementData.parentid == person.pid) {
                            beforeElement = elementData
                        }
                    }
                    console.log('parent', elementParent);
                    console.log('before', beforeElement);
                    console.log('person', person);
                    console.log('');
                    let branchPerson = document.createElement('li');
                    branchPerson.setAttribute("id", `${person.id}`);
                    let dataPerson = `<a href="#" onclick="showHide(this)" class="text-decoration-none text-dark contenido-user">
                                        <div class="">
                                            <div class="d-flex justify-content-between">
                                                <div class="img-photo">
                                                    <img src="${person.img}" alt="" class="rounded-circle">
                                                </div>
                                                <p class="mt-1" style="font-size: 12px;">${person.title}</p>
                                            </div>
                                            <p class="mb-1 name" style="font-size: 15px;"><strong>${person.name}</strong></p>
                                        </div>
                                    </a>`;

                    branchPerson.innerHTML = dataPerson
                    if (elementParent == null && beforeElement == null && person.pid == null) {
                        let listPerson = document.createElement('ul');
                        listPerson.appendChild(branchPerson);
                        dataOrg.push({
                            id: person.id,
                            branchPerson,
                            listCreate: true,
                            parentid: person.pid
                        })
                        org.appendChild(listPerson)
                    } else if (beforeElement != null) {
                        beforeElement.branchPerson.parentNode.insertBefore(branchPerson, beforeElement.branchPerson)
                        dataOrg.push({
                            id: person.id,
                            branchPerson,
                            listCreate: true,
                            parentid: person.pid
                        })
                    }
                    if (elementParent != null && beforeElement == null) {
                        let listPerson = document.createElement('ul');
                        listPerson.appendChild(branchPerson);
                        dataOrg.push({
                            id: person.id,
                            branchPerson,
                            listCreate: true,
                            parentid: person.pid
                        })
                        elementParent.branchPerson.appendChild(listPerson)
                    } else if (elementParent == null && beforeElement == null && person.pid != null) {
                        console.log('No se registra aun tu jefe', person);
                        faltantes.push(person);
                        console.log(faltantes);
                    }
                }
                if (faltantes.length > 0) {
                    addNodos(faltantes, dataOrg)
                }
            }
        })

        function showHide(e) {
            if (e.parentNode.lastElementChild.tagName == 'UL') {
                if (e.parentNode.lastElementChild.classList.toggle('d-none')) {
                    console.log(1);
                } else {

                }
            }

        }
    </script>
@endsection
