<?php
    require_once 'gestor_archivos.php'; //Incluir el archivo que contiene la clase GestorArchivos
    $gestor = new GestorArchivos(); //Crear una instancia de la clase GestorArchivos
    $archivos = $gestor->listar(); //Llamar al método listar para obtener la lista de archivos subidos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clipney</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!--Importar fuentes-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <!--Configurar fuente-->
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="bg-white min-h-screen flex flex-col">
    <!-- Header -->
    <header class="border-b border-[#D9C7C1]">
        <div class="max-w-3xl mx-auto py-6 px-6 ">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-semibold tracking-[0.12em]">CLIPNEY</h1>
                    <p class="text-sm uppercase tracking-[0.25em] text-[#6F7478] mt-2">Gestión segura de archivos</p>
                </div>
                <!--Menu de navegación-->
                <nav>
                    <ul class="flex items-center">
                        <li><a href="index.php" class="text-[#5E2A37] hover:text-amber-700 transition text-lg font-medium">Inicio</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <!--Contenido principal-->
    <main class="flex-grow max-w-3xl mx-auto w-full px-6">
        <!--Sección del formulario-->
        <section class="pb-10 pt-20">
            <h2 class="text-2xl font-medium text-center">Subir archivo</h2>
            <p class="text-center text-[#6F7478] text-sm mt-2">Solo se permiten archivos PDF, JPG y PNG</p>
            <!--Mostrar alertas de error o confirmación-->
            <?php if (isset($_GET['mensaje'])): ?>
                <div id="mensaje" class="max-w-lg mx-auto mt-6 p-4 rounded-md text-center <?= $_GET['estado'] === 'ok' ? 'bg-green-100 border border-green-200 text-green-800' : 'bg-red-100 border border-red-200 text-red-800' ?>">
                    <?= htmlspecialchars($_GET['mensaje']) ?>
                </div>
            <?php endif; ?>
            <!--Formulario para subir archivos-->
            <form action="subir.php" method="POST" enctype="multipart/form-data" class="mt-8 max-w-lg mx-auto">
                <div class="border border-[#D9C7C1] rounded-lg p-10 text-center">
                    <input type="file" name="archivo" accept=".pdf,.jpg,.png" required class="block w-full text-sm text-[#6F7478] file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-[#BF612A] file:text-white file:cursor-pointer hover:file:opacity-90"/>
                    <div class="mt-6">
                        <label for="titulo" class="block text-left text-sm font-medium text-[#014946] mb-2">Nombre del archivo</label>
                        <input type="text" name="titulo" id="titulo" maxlength="50" required placeholder="Ej. Informe de prácticas" class="w-full border border-[#D9C7C1] rounded-md px-4 py-2 focus:outline-none focus:border-[#916B2C]"/>
                    </div>
                    <button type="submit" class="w-full mt-6 bg-[#BF612A] text-white py-3 rounded-md font-medium hover:opacity-90 transition">Subir archivo</button>

                </div>
            </form>
        </section>
        <!--Sección de archivos subidos-->
        <section class="py-14">
            <div class="border-t border-[#D9C7C1] pt-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-8">
                    <h2 class="text-2xl font-medium">Archivos subidos</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-[#D9C7C1]">
                                <th class="text-left py-2 px-4 font-medium">Nombre de archivo</th>
                                <th class="text-left py-2 px-4 font-medium">Tamaño</th>
                                <th class="text-left py-2 px-4 font-medium">Fecha de carga</th>
                                <th class="text-left py-2 px-4 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Verificar si hay archivos para mostrar -->
                            <?php if (empty($archivos)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-[#6F7478]">Todavía no se han cargado archivos</td>
                                </tr>
                            <!-- Si hay archivos, los mostramos en la tabla -->
                            <?php else: ?>
                                <?php foreach ($archivos as $item): ?>
                                    <tr class="border-b border-[#D9C7C1]">
                                        <td class="py-2 px-4"><?= htmlspecialchars($item['titulo']) ?></td> <!--htmlspecialchars se utiliza para evitar problemas de seguridad al mostrar el nombre del archivo, como ataques de inyección de código-->
                                        <td class="py-2 px-4"><?=round($item['tamano'] / 1024, 2) . ' KB' ?></td> <!--Convertir el tamaño del archivo a KB y redondear a 2 decimales-->
                                        <td class="py-2 px-4"><?= $item['fecha'] ?></td>
                                        <td class="py-2 px-4">
                                            <div class="flex gap-2">
                                                <a href="descargar.php?archivo=<?= urlencode($item['archivo']) ?>" class="px-3 py-1 border border-[#014946] text-[#014946] rounded-md">Descargar</a> <!--urlencode se utiliza para codificar el nombre del archivo en la URL, evitando problemas con caracteres especiales-->
                                                <a href="eliminar.php?archivo=<?= urlencode($item['archivo']) ?>" class="px-3 py-1 border border-[#D90429] text-[#D90429] rounded-md" onclick="return confirm('¿Estás seguro de querer eliminar este archivo?');">Eliminar</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer class="border-t border-[#D9C7C1] py-6 px-6">
        <div class="max-w-3xl mx-auto text-center text-sm text-[#6F7478]">
            &copy; 2026 Clipney. Todos los derechos reservados.
        </div>
    </footer>

    <script>
        const mensaje = document.getElementById('mensaje');
        if (mensaje) {
            setTimeout(() => {
                mensaje.classList.add('opacity-0', 'transition-opacity', 'duration-500'); // Agregar clase para hacer desaparecer el mensaje
                window.history.replaceState({}, document.title, window.location.pathname); // Limpiar la URL para que no se muestre el mensaje al recargar la página
                setTimeout(() => {
                    mensaje.remove(); // Remover el mensaje del DOM después de la transición
                }, 500);
            }, 3000);
        }
    </script>
</body>
</html>