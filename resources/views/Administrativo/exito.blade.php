<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>Exito</title>

        <style>
            h3 {
                margin-left:
                8%;
            }

            .swal2-popup {
                height: 300px;
                width: 500px;

                border: solid 2px black;
                border-radius: 32px;
            }
            .swal2-title {
                color: black;
                border-bottom: solid 2px black;
            }
            .swal2-html-container {
                color: black;
                font-size: 24px;
            }
            .swal2-confirm {
                width: 450px;
                background: var(--Cinder-900, #152D65);
            }
        </style>

        @if (session('mensaje') && session('cabecera'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire ({
                        title: "{{ session('cabecera') }}",
                        text: "{{ session('mensaje') }}",
                        confirmButtonText: 'Aceptar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @if (session('origen') === 'crearEmpresa')
                                window.location.href = "{{ route('verEmpresa') }}";
                            @else
                                window.history.back();
                            @endif
                        }
                    });
                });
            </script>
        @endif
    </head>
    <body>
    </body>
    </html>
</x-app-layout>
