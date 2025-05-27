<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Crear usuario</title>

            <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

            <style>
                h1 {
                    margin-top: 1%;
                    margin-left: 8%;
                }
                h2 {
                    color: var(--Cinder-950, #040813);

                    font-weight: bold;
                }
                p {
                    margin-top: 3%;
                    margin-bottom: 2px;
                }
                label{
                    margin-bottom: 2px;

                }
                .num {
                    color: var(--Cinder-900, #152D65);
                    background-image: url("assets/elipse.svg");
                    background-size: contain;
                    background-position: left;
                    background-repeat: no-repeat;

                    width: 40px;
                    display: flex;
                    justify-content: center;

                    display: flex;
                    position: relative;
                    right: 10%;
                    bottom: -45px;
                }
                .icono {
                    filter: brightness(0) invert(0);
                }

                select {
                    appearance: none;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    background: url('assets/flecha.svg') no-repeat calc(100% - 3%) var(--Cinder-100, #E3E9FB) !important;

                    border: none;

                    font-size: 1.2rem;

                    display: flex;
                    width: 457px;
                    height: 49px;
                    padding-left: 2%;
                    justify-content: flex-end;
                    align-items: center;
                    flex-shrink: 0;
                }

                input {
                    background: var(--Cinder-100, #E3E9FB) !important;

                    border: none;

                    font-size: 1.2rem;

                    display: flex;
                    width: 457px;
                    height: 49px;
                    padding-left: 2%;
                    justify-content: flex-end;
                    align-items: center;
                    flex-shrink: 0;

                    margin-bottom: 3%;

                }

                #email{

                    margin-bottom: 30px;

                }

                .div1 {
                    position: absolute;
                    left: 10%;
                    top: 10%;
                }
                .div4 {
                    position: absolute;
                    left: 34.5%;
                    top: 28.5%;
                    margin-right:70px;
                }

                .error {
                    color: #f00;

                    position: absolute;
                    left: 10%;
                    bottom: 38%;

                    font-size: 1.2rem;
                }

                .crear {
                    color: var(--Cinder-50, #F1F5FE);
                    font-size: 32px;
                    font-style: normal;
                    font-weight: 700;
                    line-height: normal;
                    border-radius: 4px;
                    border: 2px solid var(--Cinder-900, #152D65);
                    background: var(--Cinder-900, #152D65);
                    box-shadow: 3px 4px 4px 0px rgba(0, 0, 0, 0.25);
                    display: flex;
                    width: 498px;
                    padding: 10px 0px;
                    justify-content: center;
                    align-items: center;
                    position: absolute;
                    right: 22%;
                    bottom: 5%;
                }
                .cancelar {
                    color: var(--Cinder-800, #133379);

                    font-size: 32px;
                    font-style: normal;
                    font-weight: 700;
                    line-height: normal;

                    border-radius: 4px;
                    border: 2px solid var(--Cinder-900, #152D65);
                    background: var(--Amarillo, #E59506);
                    box-shadow: 3px 4px 4px 0px rgba(0, 0, 0, 0.25);

                    display: inline-flex;
                    width: 15%;
                    padding: 10px 0px;
                    justify-content: center;
                    align-items: center;

                    position: absolute;
                    right: 5%;
                    bottom: 5%;
                }
            </style>
        </head>

        <body>
            <h1><img src="assets/Gestor/CrearEmpresa.svg" class="icono">  Crear Empresa</h1>
            <form action="{{ route('guardarEmpresa') }}" method="post">
                @csrf

                <div class="div1">
                    <h2 class="num">1</h2>
                    <h2>Datos</h2>
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" id="ciudad" name="ciudad">
                    <label for="cif">Cif</label>
                    <input type="text" id="cif" name="cif">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="div4">

                    <label for="postal">Código Postal</label>
                    <input type="text" id="postal" name="codigo_postal">

                </div>

                @error('form')
                    <div class="error"> {{ $message }} </div>
                @enderror

                <button class="crear btn btn-primary">Crear</button>
            </form>

            <a href="{{ url()->previous() }}" class="cancelar btn btn-warning">Cancelar</a>

        </body>
    </html>
</x-app-layout>
