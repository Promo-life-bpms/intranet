<!DOCTYPE html>
<html lang="eS">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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

        .conten_info .pluss {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 0;
        }

        .conten_info .pluss1 {
            position: relative;
        }

        .conten_info .pluss1 div {
            font-size: 15px;
            width: 25px;
            flex-shrink: 1;
            background-color: #fff;
            border-radius: 50%;
            border: 1px #039BE5 solid;
            position: relative;
            top: 10px;
            z-index: 100;
        }

        .conten_info .pluss1 div:hover {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="organigrama d-flex ">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
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

                    addNodos(response, [])
                    focusMe()
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

                    let branchPerson = document.createElement('li');
                    branchPerson.setAttribute("id", `${person.id}`);
                    let dataPerson = `<a class="text-decoration-none text-dark contenido-user">
                                        <div class="conten_info">
                                            <div class="d-flex justify-content-between">
                                                <div class="img-photo">
                                                    <img src="${person.img}" alt="" class="rounded-circle" style="width: 100px; height: 100px; object-fit:cover;">
                                                </div>
                                                <p class="mt-1" style="font-size: 12px;">${person.title}</p>
                                            </div>
                                            <p class="mb-1 name" style="font-size: 15px;"><strong>${person.name}</strong></p>
                                            <div class="pluss" >
                                                <div class="pluss1">
                                                    <div onclick="showHide(this, event)">+</div>
                                                </div>
                                            </div>
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

                        faltantes.push(person);

                    }
                }
                if (faltantes.length > 0) {
                    addNodos(faltantes, dataOrg)
                }

                const nodos_finish = document.querySelectorAll('.contenido-user')

                nodos_finish.forEach(element => {
                    if (element.parentNode.lastElementChild.tagName !== 'UL') {
                        element.lastElementChild.lastElementChild.lastElementChild.lastElementChild
                            .classList.add('d-none')
                    }
                });

            }
        })

        function focusMe() {
            var i = 0,
                d = document,
                inp = Array.from(d.querySelectorAll(".contenido-user")),
                max = inp.length,
                sdiv = d.querySelector(".organigrama");

            for (; i < max; i++) {
                var e = inp[i],
                    equation = e.addEventListener("click", (e) => {
                        console.log(e);
                        var elemento = e.srcElement;
                        console.log(elemento.offsetLeft - (sdiv.clientWidth / 2), 0);
                        sdiv.scrollTo(elemento.offsetLeft - (sdiv.clientWidth / 2), 0);
                    });
                console.log(e);
            }
        }



        function showHide(element, event) {
            element.parentNode.parentNode.parentNode.parentNode.parentNode.lastElementChild.classList.toggle('d-none')
            const org = document.querySelector('.organigrama')
            var sLeft = element.scrollLeft;
            // justify-content-center

        }
    </script>
</body>

</html>
