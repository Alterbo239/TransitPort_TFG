<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registrar Vehiculo</title>

            <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </head>
        <body>
            <h1 class="titulo"><img src="assets/Client/RegistrarVehiculo.svg">  Registrar Transporte</h1>
            <form action="{{ route('storeVehiculo') }}" method="post">
                @csrf

                <div class="container">
                    <div class="div div1">
                        <h2 class="num">1</h2>
                        <h2>Datos</h2>
                        <div class="movile">
                            <p>Nombre</p>
                            <input type="text" name="nombre">
                        </div>

                        <div class="movile">
                            <p>Seleccionar tipo</p>
                            <select class="hora" name="tipo">
                                <option>Buque</option>
                                <option>Trailer</option>
                            </select>
                        </div>
                    </div>

                    <div class="div div2">
                        <h2 class="num">2</h2>
                        <h2>Datos de la empresa</h2>
                        <p>Empresa</p>
                        <select name="empresa" id="empresa">
                            @forelse ($empresas as $empresa)
                                <option value="{{ $empresa -> id }}"> {{ $empresa -> nombre }} </option>
                            @empty
                                <p>No hay empresas disponibles.</p>
                            @endforelse
                        </select>
                        <p>Ciudad</p>
                        <input type="text" id="ciudad" name="ciudad" value="{{ $empresas -> first() -> ciudad ?? '' }}" readonly>
                    </div>
                </div>

                <button class="crear btn">Registrar</button>
            </form>
            <form action="" method="get">
                <button class="cancelar btn">Cancelar</button>
            </form>
        </body>
    </html>

    <script>
        // Convertimos los datos de las empresas a json para usarlo en el js.
        const empresasData = @json($empresas);

        // Agregamos un evento para que, cada vez que la empresa cambie en su select, tambien lo haga la ciudad.
        document.getElementById('empresa').addEventListener('change', function() {
            const empresaID = this.value;
            let city = empresasData[empresaID] ? empresasData[empresaID].ciudad : 'No disponible.'; // Le ponemos el valor encontrado o mostramos mensaje default.
            document.getElementById('ciudad').value = city; // Agregamos el valor al select.
        })
    </script>

    <style>
        h1 {
            margin-top: 1%;
            margin-left: 8%;
        }
        h1 img {
            width: 40px;
            filter: brightness(0) invert(0);
        }
        h2 {
            color: var(--Cinder-950, #040813);

            font-weight: bold;
        }
        p {
            margin-top: 3%;
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
        .titulo {
            width: 420px;

            display: flex;
            align-items: center;
            justify-content: space-between;
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
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: var(--Cinder-100, #E3E9FB);

            border: none;

            font-size: 1.2rem;

            display: inline-block;
            padding: 0% 2%;
            width: 457px;
            height: 49px;
            align-items: center;
        }
        input[readonly] {
            background: var(--Cinder-50, #F1F5FE);
        }

        .div1 {
            position: absolute;
            left: 12%;
            top: 8%;
        }
        .div2 {
            position: absolute;
            left: 12%;
            top: 46%;
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
</x-app-layout>
