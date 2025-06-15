<x-app-layout>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Ver Empresas</title>
            <meta charset="utf-8">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
            <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

            <link rel="stylesheet" href="assets/css/datatables.css">
        </head>
        <body>
            
        <div class="container mt-5">
            <button id="crearEmpresa" type="button" class="btn btn-info botonCrear">
                <img src="./assets/Gestor/CrearEmpresa.svg" class="botonImagen" alt="Crear Empresa">
                Crear Empresa
            </button>
            <button id="btnFiltrar" type="button" class="btn btn-info botonFiltrar">
                <img src="./assets/vector.svg" class="botonImagen3" alt="Filtrar">
                Filtrar
            </button>
            
            <h2 class="mb-4">Visualizar Empresas</h2>
            <table id="empresas" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Ciudad</th>
                        <th>Cif</th>
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
            document.getElementById('crearEmpresa').addEventListener('click', function () {
                window.location.href = '/crearEmpresa';
            });

            $(document).ready(function () {
                let table = $('#empresas').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("verEmpresas") }}',
                    columns: [
                        { data: 'nombre', name: 'nombre' },
                        { data: 'ciudad', name: 'ciudad' },
                        { data: 'cif', name: 'cif' },
                    ]
                });

                $('#empresas tbody').on('click', 'tr', function() {
                    let empresa = table.row(this).data();
                    
                    Swal.fire({
                        title: 'Detalles de la Empresa',
                        html: `
                            <p><span style="text-decoration: underline">${empresa.nombre}</span> </p><br>

                            <div class="div1">
                            <label for="ciudad"><span>Ciudad</span></label><br>
                            <input id="ciudad" class="swal2-input" type="text" value="${empresa.ciudad}" readonly><br>

                            <label for="codigoPostal"><span>Código Postal</span></label><br>
                            <input id="codigoPostal" class="swal2-input" type="text" value="${empresa.codigo_postal}" readonly><br>

                            <label for="cif"><span>CIF</span></label><br>
                            <input id="cif" class="swal2-input" type="cif" value="${empresa.cif}" readonly><br>

                            <label for="email"><span>Email</span></label><br>
                            <input id="email" class="swal2-input" type="email" value="${empresa.email}" readonly><br>
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
                                title: 'Actualizar Empresa',
                                html: `
                                    <h3>Nombre</h3>
                                    <input class="infoInput" id="nombre" style="border: solid 1px black" type="text" value="${empresa.nombre || ''}">
                                    <br>
                                    <h3>Ciudad</h3>
                                    <input class="infoInput" id="ciudad" style="border: solid 1px black" type="text" value="${empresa.ciudad || ''}">
                                    <br>
                                    <h3>Codigo Postal</h3>
                                    <input class="infoInput" id="codigo_postal" style="border: solid 1px black" type="text" value="${empresa.codigo_postal || ''}">
                                    <br>
                                    <h3>CIF</h3>
                                    <input class="infoInput" id="cif" style="border: solid 1px black" type="text" value="${empresa.cif || ''}">
                                    <br>
                                    <h3>Email</h3>
                                    <input class="infoInput" id="email" style="border: solid 1px black" type="email" value="${empresa.email || ''}">
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
                                preConfirm: () => {
                                    let nombre = document.getElementById('nombre').value;
                                    let ciudad = document.getElementById('ciudad').value;
                                    let codigo_postal = document.getElementById('codigo_postal').value;
                                    let cif = document.getElementById('cif').value;
                                    let email = document.getElementById('email').value;

                                    let dataValida = false;

                                    // Comprobamos que estos datos cumplen los requisitos.
                                    const codigoPostalValido = /^\d{5}$/.test(codigo_postal);
                                    const cifValido = /^\d{8}[A-Za-z]$/.test(cif);
                                    const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

                                    // Validando los datos -> .
                                    if ( nombre && ciudad && codigoPostalValido && cifValido && emailValido ) {
                                    dataValida = true;
                                    }

                                    if ( dataValida ) {
                                        return { nombre, ciudad, codigo_postal, cif, email };
                                    } else {
                                        Swal.showValidationMessage('Uno o más campos no son válidos, asegúrate de que los datos sean correctos');
                                        return false;
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    let data = result.value;

                                    $.ajax({
                                        url: '{{ route("actualizarEmpresa") }}',
                                        type: 'POST',
                                        data: {
                                            _token: $('meta[name="csrf-token"]').attr('content'),
                                            id: empresa.id,
                                            nombre: data.nombre,
                                            ciudad: data.ciudad,
                                            codigo_postal: data.codigo_postal,
                                            cif: data.cif,
                                            email: data.email,
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
                $.get('/getCiudades', function (ciudades) {
                    let opciones = ciudades.map(ciudad => `<option value="${ciudad}">${ciudad}</option>`).join('');

                    Swal.fire({
                        title: 'Filtrar por ciudad',
                        html: `
                            <select id="selectCiudad" class="swal2-select">
                                <option value="">Todos</option>
                                ${opciones}
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
                                ciudad: document.getElementById('selectCiudad').value
                            }
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            let ciudad = result.value.ciudad;
                            let table = $('#empresas').DataTable();
                            table.column(1).search(ciudad).draw(); // Ajusta el índice si ciudad no está en columna 1
                        }
                    });
                });
            });
        </script>
    </html>
</x-app-layout>