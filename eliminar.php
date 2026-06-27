<?php
    require_once 'gestor_archivos.php'; //Incluir el archivo que contiene la clase GestorArchivos
    $nombreArchivo = $_GET['archivo'] ?? ''; // Obtener el nombre del archivo desde la URL
    $gestor = new GestorArchivos(); //Crear una instancia de la clase GestorArchivos
    $resultado = $gestor->eliminar($nombreArchivo); //Llamar al método eliminar pasando el nombre del archivo a eliminar

    header('Location: index.php?estado=' . ($resultado['estado'] ? 'ok' : 'error') . '&mensaje=' . urlencode($resultado['mensaje'])); //Redirigir a index.php con el estado y mensaje de la eliminación
    exit;
?>