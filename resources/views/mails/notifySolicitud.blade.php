<!DOCTYPE html>
<html>
    <head>
        <title>Título de la página</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

        <style>
            .center-text{
                text-align: center;
            }

            .custom-h1{
                font-size: 36px;
                margin-top: 50px;
                margin-bottom: 20px;
            }
        </style>
    </head>
        <body>
            <div class="container">
                <h1 class="custom-h1 center-text">¡Se ha generado una nueva solicitud!</h1> 
                <p>Para visualizar las nuevas solicitudes y sus detalles da clic al siguiente botón.</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('team.admon') }}" class="btn btn-warning">Ver solicitudes</a>
                    </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>    
        </body>
</html>
  