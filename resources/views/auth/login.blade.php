<x-guest-layout>
    <div id="bloqueInicio" class="d-flex align-items-center justify-content-center vh-100" >
        <div class="d-flex">

            <!-- <form action="{{ route('loginCliente') }}" method="get">
                <button type="submit"
                        class="px-10 py-3 mt-6 text-lg font-bold text-white transition bg-gray-900 rounded-lg shadow-md botonLogin hover:bg-gray-800"
                        style="margin-right: 40px">
                    Entrar como Cliente
                </button>
            </form> -->

            <div id="bloqueLogin" class="p-4 d-flex flex-column align-items-center justify-content-center">
                <div id="contenidoLogin">

                    <div class="bloqueLogo d-flex align-items-center">
                        <img id="logo" src="/assets/Logo.png" alt="Logo">
                        <p>
                            <span class="font-mono text-blue-300 transit">RANSIT</span>
                            <span class="font-mono text-white guion">-</span>
                            <span class="font-mono text-gray-300 port">PORT</span>
                        </p>
                    </div>

                    <p id="welcome">Inicia sesión para acceder a tu cuenta</p>
                    <p id="welcome2">¡Bienvenido!</p>
                    <hr class="">

                    <form method="POST" action="{{ route('login') }}" class="w-100 d-flex flex-column align-items-center">
                        @csrf

                        <input class="form-control" type="email" name="email" placeholder="E-mail" required>

                        <input id="password" class="form-control" type="password" name="password" placeholder="Contraseña" required>
                        <img id="verContrasenya" class="icono-ojo" src="{{ asset('assets/Login/eye.svg') }}" onclick="ocultarContrasenya()" alt="Mostrar contraseña">

                        <div class="form-check d-flex align-items-center recordarForm">
                            <input id="recordar" type="checkbox" class="form-check-input me-2 recordarCheck" name="remember">
                            <label for="recordar" class="recordarUser form-check-label">Recordar usuario</label>
                        </div>

                        <button type="submit" class="px-10 py-3 mt-6 text-lg font-bold text-white transition bg-gray-900 rounded-lg shadow-md botonLogin hover:bg-gray-800">
                            INICIAR SESIÓN
                        </button>
                    </form>
                </div>
            </div>

            <!--imagen-->
            <div id="imagenInicio" class="w-[586px] h-[817px]">
                <img id="imagenIni" class="w-full h-full" src="{{ asset('assets/Login/group-16.png') }}" alt="Imagen de Login">
            </div>
        </div>
    </div>

    <script>
        let mostrarContrasenya = false;
        let passwordTipo = document.getElementById('password');
        let iconoMostrarContrasenya = document.getElementById('verContrasenya');

        //cambiar la visibilidad de la contraseña con el ojo cerrado o abierto
        function ocultarContrasenya() {

            if (mostrarContrasenya) {
                passwordTipo.type = 'password';
                iconoMostrarContrasenya.src = '/assets/Login/eyeClosed.svg'; // Ojo abierto
            } else {
                passwordTipo.type = 'text'; // Si está oculta, la mostramos
                iconoMostrarContrasenya.src = '/assets/Login/eye.svg'; // Ojo cerrado
            }

            mostrarContrasenya = !mostrarContrasenya;
        }
    </script>
</x-guest-layout>
