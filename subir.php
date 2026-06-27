<?php
    require_once 'gestor_archivos.php'; //Incluir el archivo que contiene la clase GestorArchivos
    $gestor = new GestorArchivos(); //Crear una instancia de la clase GestorArchivos
    $resultado = $gestor->subir($_FILES['archivo'], $_POST['titulo']); //Llamar al método subir pasando el archivo subido a través del formulario y el título
    
    header('Location: index.php?estado=' . ($resultado['estado'] ? 'ok' : 'error') . '&mensaje=' . urlencode($resultado['mensaje'])); //Redirigir a index.php con el estado y mensaje de la subida

    exit;
?>