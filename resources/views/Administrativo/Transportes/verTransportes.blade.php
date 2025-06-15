<x-app-layout>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Ver Transportes</title>
            <meta charset="utf-8">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
            <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

            <link rel="stylesheet" href="assets/css/datatables.css">
        </head>
        <body>
            
        <div class="container mt-5">
            <button id="crearTransporte" type="button" class="btn btn-info botonCrear botonCrearTransporte">
                <img src="./assets/Client/RegistrarVehiculo.svg" class="botonImagen" alt="Crear Transporte">
                Crear Transporte
            </button>
            <button id="btnFiltrar" type="button" class="btn btn-info botonFiltrar">
                <img src="./assets/vector.svg" class="botonImagen3" alt="Filtrar">
                Filtrar
            </button>
            
            <h2 class="mb-4">Visualizar Transportes</h2>
            <table id="transportes" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Empresa</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        
        </body>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script type="text/javascript">
            const id_administrativo = {{ Auth::id() }};

            document.getElementById('crearTransporte').addEventListener('click', function () {
                window.location.href = '/registrarTransporte';
            });

            $(document).ready(function () {
                let table = $('#transportes').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("verTransportes") }}',
                    columns: [
                        { data: 'nombre', name: 'nombre' },
                        { data: 'tipo', name: 'tipo' },
                        { data: 'empresa', name: 'empresa' },
                        { data: 'estado', name: 'estado' },
                    ]
                });

                $('#transportes tbody').on('click', 'tr', function() {
                    let transporte = table.row(this).data();
                    
                    Swal.fire({
                        title: 'Detalles del Transporte',
                        html: `
                            <p><span style="text-decoration: underline">${transporte.nombre}</span> </p><br>

                            <div class="div1">
                            <label for="tipo"><span>Tipo</span></label><br>
                            <input id="tipo" class="swal2-input" type="text" value="${transporte.tipo}" readonly><br>

                            <label for="empresa"><span>Empresa</span></label><br>
                            <input id="empresa" class="swal2-input" type="text" value="${transporte.empresa}" readonly><br>

                            <label for="estado"><span>Estado</span></label><br>
                            <input id="estado" class="swal2-input" type="text" value="${transporte.estado}" readonly><br>
                            </div>
                        `,
                        confirmButtonText: 'Actualizar',
                        cancelButtonText: 'Cerrar',
                        
                        showCancelButton: true,
                        
                        customClass: {
                            popup: "mi-popup",
                            title: "mi-titulo",
                            confirmButton: "mi-boton",
                            cancelButton: "mi-boton-cancelar",
                            closeButton: "mi-cruz",
                            htmlContainer: "misCosas2"
                        }
                    }). then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Actualizar Transporte',
                                html: `
                                    <h3>Nombre</h3>
                                    <input class="infoInput" id="nombre" style="border: solid 1px black" type="text" value="${transporte.nombre || ''}">
                                    <br>
                                    <h3>Tipo</h3>
                                    <select class="infoInput" id="tipo" style="border: solid 1px black">
                                        <option value="buque">Buque</option>
                                        <option value="trailer">Trailer</option>
                                    </select>
                                    <br>
                                    <h3>Empresas</h3>
                                    <select class="infoInput" id="empresa" style="border: solid 1px black">
                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <br>
                                    <h3>Estado</h3>
                                    <select class="infoInput" id="estado" style="border: solid 1px black">
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                    <br>
                                `,
                                confirmButtonText: 'Aceptar',
                                cancelButtonText: 'Cerrar',
                                
                                showCancelButton: true,

                                customClass: {
                                    popup: "mi-popup",
                                    title: "mi-titulo",
                                    confirmButton: "mi-boton2",
                                    cancelButton: "mi-boton-cancelar2",
                                    closeButton: "mi-cruz",
                                    htmlContainer: "misCosas"
                                },
                                didOpen: () => {
                                    document.getElementById('tipo').value = transporte.tipo;
                                    document.getElementById('empresa').value = transporte.id_empresa;
                                    document.getElementById('estado').value = transporte.estado;
                                },
                                preConfirm: () => {
                                    let nombre = document.getElementById('nombre').value;
                                    let tipo = document.getElementById('tipo').value;
                                    let empresa = document.getElementById('empresa').value;
                                    let estado = document.getElementById('estado').value;

                                    let dataValida = false;

                                    // Validando los datos -> .
                                    if ( nombre.length >= 3 && tipo && empresa && estado ) {
                                        dataValida = true;
                                    }

                                    if ( dataValida ) {
                                        return { nombre, tipo, empresa, estado };
                                    } else {
                                        Swal.showValidationMessage('Uno o más campos no son válidos, asegúrate de que los datos sean correctos');
                                        return false;
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    let data = result.value;

                                    $.ajax({
                                        url: '{{ route("actualizarTransporte") }}',
                                        type: 'POST',
                                        data: {
                                            _token: $('meta[name="csrf-token"]').attr('content'),
                                            id: transporte.id,
                                            nombre: data.nombre,
                                            tipo: data.tipo,
                                            id_empresa: data.empresa,
                                            estado: data.estado,
                                            id_administrativo: id_administrativo,
                                        },
                                        success: function(response) {
                                            Swal.fire({
                                                title: 'Éxito',
                                                text: 'Empresa actualizada correctamente.',
                                                icon: 'success'
                                            });
                                            table.ajax.reload();
                                        }
                                    });
                                }
                                    
                            });
                        }
                    });
                });
            });

            $('#btnFiltrar').on('click', function () {
                let tipo = ['Buque', 'Trailer'];
                let opcionesTipo = tipo.map(t => `<option value="${t}">${t}</option>`).join('');

                let empresas = @json($empresas);
                let opcionesEmpresas = empresas.map(e => `<option value="${e.nombre}">${e.nombre}</option>`).join('');

                let estado = ['Activo', 'Inactivo'];
                let opcionesEstado = estado.map(e => `<option value="${e}">${e}</option>`).join('');

                Swal.fire({
                    title: 'Filtrar por ciudad',
                    html: `
                        <label for="selectTipo">Tipo:</label>
                        <br>
                        <select id="selectTipo" class="swal2-select">
                            <option value="">Todos</option>
                            ${opcionesTipo}
                        </select>
                        <br><br>
                        <label for="selectEmpresa">Empresa:</label>
                        <br>
                        <select id="selectEmpresa" class="swal2-select">
                            <option value="">Todas</option>
                            ${opcionesEmpresas}
                        </select>
                        <br><br>
                        <label for="selectEstado">Estado:</label>
                        <br>
                        <select id="selectEstado" class="swal2-select">
                            <option value="">Todos</option>
                            ${opcionesEstado}
                        </select>
                    `,
                    confirmButtonText: 'Aplicar filtro',
                    showCancelButton: true,                        
                    customClass: {
                        popup: "mi-popup2",
                        title: "mi-titulo2",
                        confirmButton: "mi-boton2",
                        closeButton: "mi-cruz2",
                        htmlContainer: "misCosas"
                    },
                    preConfirm: () => {
                        return {
                            tipo: document.getElementById('selectTipo').value,
                            empresa: document.getElementById('selectEmpresa').value,
                            estado: document.getElementById('selectEstado').value
                        }
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        let tipo = result.value.tipo;
                        let empresa = result.value.empresa.trim();
                        let estado = result.value.estado;

                        console.log(tipo, empresa, estado);

                        let table = $('#transportes').DataTable();
                        if (tipo) {
                            table.column(1).search(tipo);
                        } else {
                            table.column(1).search('');
                        }

                        if (empresa) {
                            table.column(2).search(empresa, true, false);
                        } else {
                            table.column(2).search('');
                        }

                        if (estado) {
                            table.column(3).search('^' + estado + '$', true, false);
                        } else {
                            table.column(3).search('');
                        }
                        table.draw();
                    }
                });
            });
        </script>
    </html>
</x-app-layout>