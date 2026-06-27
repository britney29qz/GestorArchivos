<?php
    $archivo =$_GET['archivo'] ?? ''; // Obtener el nombre del archivo desde la URL, si no se proporciona, se asigna una cadena vacía
    
    if (empty($archivo)) { // Verificar si el nombre del archivo está vacío
        die('Archivo no válido.'); 
    }
    $rutaArchivo = 'uploads/' . basename($archivo); // Construir la ruta completa del archivo utilizando basename para evitar problemas de seguridad con rutas relativas
    
    // Verificar si el archivo no existe en la ruta especificada
    if (!file_exists($rutaArchivo)) { 
        die('Archivo no encontrado.'); 
    }

    //Obtener el nombre de archivo que verá el usuario con pahtinfo
    $nombreSinExtension = pathinfo($archivo, PATHINFO_FILENAME); 
    $extensionArchivo = pathinfo($archivo, PATHINFO_EXTENSION); 
    $partes = explode('__', $nombreSinExtension, 2); // Dividir el nombre del archivo en partes utilizando "__" como delimitador
    if (isset($partes[1])) { // Verificar si hay un título presente en las partes
        $titulo = str_replace('_', ' ', $partes[1]); // Reemplazar los guiones bajos por espacios en el título
    } else {
        $titulo = $nombreSinExtension; // Si no hay título, utilizar el nombre del archivo sin la extensión como título
    }
    $nombreArchivoDescarga = $titulo . '.' . $extensionArchivo; 

    header('Content-Description: File Transfer'); // Enviar encabezados HTTP para indicar que se va a transferir un archivo
    header('Content-Type: application/octet-stream'); // Indicar que el tipo de contenido es un flujo de bytes genérico
    header('Content-Disposition: attachment; filename="' . $nombreArchivoDescarga . '"'); // Indicar que el archivo debe descargarse como un adjunto con el nombre de descarga
    header('Content-Length: ' . filesize($rutaArchivo)); // Indicar la longitud del contenido en bytes
    readfile($rutaArchivo); // Leer el archivo y enviarlo al navegador para su descarga
    
    exit;
?>