<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pedir Citas</title>

        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Calendario -->
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    </head>

    <body>
        <h1 class="titulo"><img src="assets/Administrativo/crearTurnoVer.svg">  Pedir Cita</h1>
        <form action="{{ route('storePedirCitas') }}" method="post">
            @csrf

            <div id='fondo' style="visibility: hidden;">
                <div id='calendario'></div>
                <div class="botones-calendar">
                    <button type="button" id="confirm" class="crear2 btn" disabled>Confirmar</button>
                    <button type="button" id="cancel" class="cancelar2 btn">Cancelar</button>
                </div>
            </div>

            <div class="container">
                <div class="div div1">
                    <h2 class="num">1</h2>
                    <h2>Fecha</h2>
                    <div class="movile">
                        <p>Seleccionar fecha</p>
                        <input class="date" type="date" name="fecha" id="fecha" readonly><img src="assets/Administrativo/calendario.svg" id="icono">
                    </div>

                    @error('fecha')
                        <div class="error"> La fecha seleccionada debe ser una fecha futura. </div>
                    @enderror
                </div>

                <div class="div div2">
                    <h2 class="num">2</h2>
                    <h2>Transporte</h2>
                    <div class="movile">
                        <p>Seleccionar transporte</p>
                        <select class="hora" name="buque">
                            @forelse ($buques as $buque)
                                <option value="{{ $buque -> id }}"> {{ $buque -> nombre }} </option>
                            @empty
                                <option>No hay buques todavía</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="div div3">
                    <h2 class="num">3</h2>
                    <h2>Tipo</h2>
                    <div class="movile">
                        <p>Seleccionar tipo</p>
                        <select class="hora" name="tipo">
                            <option value="carga">Carga</option>
                            <option value="descarga">Descarga</option>
                        </select>
                    </div>
                </div>
            </div>

            <button class="crear btn">Solicitar</button>
        </form>
        <form action="" method="get">
            <button class="cancelar btn">Cancelar</button>
        </form>
    </body>
</html>

<script>
    window.addEventListener('load', inicio);

    function inicio() {
        document.getElementById('icono').addEventListener('click', mostrarCalendario);

        document.getElementById('confirm').addEventListener('click', confirmCalendar);
        document.getElementById('cancel').addEventListener('click', cancelCalendar);
    }

    /**
     * Funcion para mostrar u ocultar el calendario.
     */
    function mostrarCalendario() {
        let calendario = document.getElementById('fondo');

        if (calendario.style.visibility != 'hidden') {
            calendario.style.visibility = 'hidden';
        } else {
            calendario.style.visibility = 'visible';
        }
    }

    /**
     * Funcion que confirma el valor del calendario y vuelve invisible el calendario.
     */
    function confirmCalendar() {
        document.getElementById('fondo').style.visibility = 'hidden';
    }
    /**
     * Funcion que cancela la operacion del calendario, volviendo nulo el valor.
     */
    function cancelCalendar() {
        document.getElementById('fecha').value = null;

        // Desactivamos las celdas, pues ya no esta seleccionada ninguna.
        let celdas = document.querySelectorAll('.fc-day.selected');
        celdas.forEach(function(celda) {
            celda.classList.remove('selected');

            let numSelected = celda.querySelector('.fc-daygrid-day-number');
            if (numSelected) {
                numSelected.style.color = '';
            }
        });

        document.getElementById('confirm').disabled = true; // Desabilitamos el boton de confirmar.
        confirmCalendar();
    }

    document.addEventListener('DOMContentLoaded', function() {
        let calendar = document.getElementById('calendario');
        let calendario = new FullCalendar.Calendar(calendar, {
            initialView: 'dayGridMonth', //Vista del calendario. (Mes fraccionado por dias).
            locale: 'es', //Cambiamos al Castellano.

            /**
            * Funcion para actualizar el "input date" al seleccionar un dia en el calendario.
            * info: Dia seleccionado.
            */
            dateClick: function(info) {
                let fechaSeleccionada = info.dateStr; //Convertimos la fecha a formato YYYY-MM-DD.

                /**
                * Primero comprobamos que las celdas no esten seleccionadas. Y si lo estan, las deseleccionamos.
                * En principio solo hay una fecha seleccionada, pero por si fallase indicamos que coja todas las celdas "seleccionadas".
                */
                let celdas = document.querySelectorAll('.fc-day.selected');
                celdas.forEach(function(celda) {
                    celda.classList.remove('selected');

                    //A partir de la celda que hayamos cogido, le cambiamos el color del numero de la celda.
                    let numSelected = celda.querySelector('.fc-daygrid-day-number');
                    if (numSelected) {
                        numSelected.style.color = '';
                    }
                });

                //Ahora pillamos la celda con la fecha actual y le agregamos la calse "selected".
                let celda = document.querySelector(`[data-date="${fechaSeleccionada}"`);
                if (celda) {
                    celda.classList.add('selected');

                    //Al igual que antes, le cambiamos el color del numero de la celda para que sea más legible.
                    let num = celda.querySelector('.fc-daygrid-day-number');
                    if (num) {
                        num.style.color = 'white';
                    }

                    document.getElementById('confirm').disabled = false;
                }

                //Cogemos el input y le actualizamos el valor.
                let input = document.getElementById('fecha');
                input.value = fechaSeleccionada;
            }
        });

        //Generamos el calendario.
        calendario.render();
    });
</script>

<style>
    *, *::before, *::after {
        box-sizing: border-box;
    }
    html, body {
        margin: 0;
        padding: 3% 0px;
        overflow: hidden;
        max-width: 100vw;
        box-sizing: border-box;
    }
    h1 {
        font-size: 2rem;
        margin-left: 6%;
    }

    h2 {
        color: var(--Cinder-950, #040813);

        font-weight: bold;
        font-size: 1.5rem;
        margin-left: 5%;
    }

    p {
        font-size: 1.2rem;

        margin-bottom: 2px;
    }
    .num {
        color: var(--Cinder-900, #152D65);
        background-image: url("assets/elipse.svg");
        background-size: contain;
        background-position: left;
        background-repeat: no-repeat;

        font-size: 1.8rem;
        font-weight: bolder;

        width: 40px;
        display: flex;
        justify-content: center;
        align-items: center;

        display: flex;
        position: relative;
        right: 13%;
        bottom: -38px;
    }

    .error {
        font-size: 0.9rem;
        color: #f00;
    }

    .titulo {
        width: 100%;

        font-weight: bold;

        display: flex;
        align-items: start;
    }
    .titulo img {
        width: 10%;

        margin-right: 3%;
    }

    select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: url('assets/flecha.svg') no-repeat calc(100% - 3%) var(--Cinder-100, #E3E9FB) !important;

        border: none;

        font-size: 1.2rem;

        display: flex;
        width: 100%;
        height: 2rem;
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
        width: 100%;
        height: 2rem;
        align-items: center;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: url('assets/Administrativo/calendario.svg') no-repeat;
        background-size: 100%;
        background-position: center;
        pointer-events: none;
    }
    #icono {
        width: 8%;
        position: absolute;
        bottom: 3px;
        right: 3%;
        cursor: pointer;
    }

    #fecha {
        pointer-events: none;
    }

    .container {
        display: flex;
        justify-content: center;  /* Centra horizontalmente */
        align-items: center;
    }
    .div {
        width: 90%;
        max-width: 100%;
        margin-left: 10%;
        position: absolute;
    }
    .div1 {
        top: 8%;
    }
    .div2 {
        top: 28%;
    }
    .div3 {
        top: 48%;
    }

    .movile {
        position: relative;

        right: 8%;
    }

    /* ------------------------- Estilo del calendario ------------------------- */
    #calendario {
        margin: 20% 7.5%;
        padding: 10px;

        height: 60%;
        width: 80%;
        max-width: 100%;

        background-color: #fff;

        z-index: 10;
    }
    #fondo {
        position: fixed;
        top: 0;
        left: 0;

        width: 100%;
        height: 100%;

        background-color: rgba(0, 0, 0, 0.5);

        z-index: 8;
        overflow: hidden;
    }
    .botones-calendar {
        height: 10%;
        width: 80%;

        margin: -21% 7.5%;
        background-color: #fff;
    }
    .botones-calendar button {
        font-size: 1rem;

        margin: 4% 0%;
        width: 25%;
        height: 4%;

        display: flex;
        position: absolute;
    }

    .botones-calendar .crear {
        margin-right: 12%;
    }
    .botones-calendar .cancelar {
        margin-left: 8%;
    }

    :root {
        --fc-border-color: none;
        --fc-today-bg-color:rgba(35, 97, 212, 0.2);
    }
    .fc .fc-col-header-cell-cushion, .fc-daygrid-day-number {
        color: var(--Light-Primary, #333);
        text-decoration: none;
    }
    .fc-daygrid-day-frame {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .fc-prev-button, .fc-next-button {
        background-color: var(--Cinder-200, #C0D2F7) !important;
        border: none !important;
        margin: 0px 2% !important;
        border-radius: 16px !important;
        width: 40px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }
    .fc-icon {
        color: #152D65;
    }
    .fc-day:hover, .fc-daygrid-day.fc-day-today:hover {
        color: #040813;
        background-color: #C0D2F7 !important;
    }
    .fc-day.selected {
        background-color: #2362D4 !important;
    }
    .fc-day {
        background-color: none;
    }

    .btn {
        font-size: 1.5rem;
        font-style: normal;
        font-weight: 700;
        line-height: normal;

        border-radius: 4px;
        border: 2px solid var(--Cinder-900, #152D65);
        box-shadow: 3px 4px 4px 0px rgba(0, 0, 0, 0.25);

        width: 40%;
        height: 5%;
        padding: 10px 0px;
        justify-content: center;
        align-items: center;

        position: absolute;
        bottom: 22%;
    }
    .crear {
        color: var(--Cinder-50, #F1F5FE);

        background: var(--Cinder-900, #152D65);

        display: flex;

        left: 6%;
    }
    .cancelar {
        color: var(--Cinder-800, #133379);

        border-radius: 4px;
        border: 2px solid var(--Cinder-900, #152D65);
        background: var(--Amarillo, #E59506);
        box-shadow: 3px 4px 4px 0px rgba(0, 0, 0, 0.25);

        display: inline-flex;

        right: 6%;
    }

    .crear2 {
        color: var(--Cinder-50, #F1F5FE);

        background: var(--Cinder-900, #152D65);

        display: flex;
        left: 12%;
    }
    .cancelar2 {
        color: var(--Cinder-800, #133379);

        border-radius: 4px;
        border: 2px solid var(--Cinder-900, #152D65);
        background: var(--Amarillo, #E59506);
        box-shadow: 3px 4px 4px 0px rgba(0, 0, 0, 0.25);

        display: flex;
        right: 18%;
    }
</style>
