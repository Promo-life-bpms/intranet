@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3 class="text-center">Empleados del Mes de la Evaluacion 360</h3>
    </div>
    <div class="card-body">
        <div class="d-flex w-100 justify-content-around content-employees">
        </div>
    </div>
@stop

@section('scripts')
    <script>
        const dataHtml = document.querySelector('.content-employees')
        async function obtenerEmpleados() {
            try {
                let res = await axios.get('https://evaluacion.promolife.lat/api/empleado-del-mes');
                let data = res.data;

                dataHtml.innerHTML = `
                                    <div class="card text-center shadow p-3 mx-5 bg-body rounded">
                                        <img src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"
                                            alt="Card image cap">
                                        <h5 class="card-title">${data[0].name +' '+ data[0].lastname} </h5>
                                        <p class="card-text">${data[0].puesto}</p>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <p class="card-text m-0 mx-1">${data[0].star}</p>
                                            <div style="width: 30px;" class="mx-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card text-center shadow p-3 mx-5 bg-body rounded">
                                        <img src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"
                                            alt="Card image cap">
                                            <h5 class="card-title">${data[1].name +' '+ data[1].lastname} </h5>
                                            <p class="card-text">${data[1].puesto}</p>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <p class="card-text m-0 mx-1">${data[1].star}</p>
                                                <div style="width: 30px;" class="mx-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="card text-center shadow p-3 mx-5 bg-body rounded">
                                        <img src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"
                                            alt="Card image cap">
                                            <h5 class="card-title">${data[2].name +' '+ data[2].lastname} </h5>
                                            <p class="card-text">${data[2].puesto}</p>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <p class="card-text m-0 mx-1">${data[2].star}</p>
                                                <div style="width: 30px;" class="mx-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </div>
                                            </div>
                                    </div>`
            } catch {
                console.log(error);
            }
        }
        obtenerEmpleados()
    </script>
@endsection
