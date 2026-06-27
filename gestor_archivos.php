<?php
    //Clase para gestionar los archivos subidos
    class GestorArchivos {
        private $directorio;
        public function __construct() { //Constructor para definir el directorio donde se guardarán los archivos subidos
            $this->directorio = 'uploads/';
        }


        public function subir($archivo, $titulo) {
            //Verificar que exista un archivo
            if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) { //UPLOAD_ERR_OK es una constante que indica que no hubo errores al subir el archivo
                return [
                    'estado' => false,
                    'mensaje' => 'Error al subir el archivo'
                ];
            }
            //Verificar que exista el titulo del archivo
            $titulo = strip_tags(trim($titulo)); // strip_tags elimina las etiquetas HTML del título y trim elimina los espacios en blanco al inicio y al final del título
            if (empty($titulo)) {
                return [
                    'estado' => false,
                    'mensaje' => 'Debe ingresar un nombre para el archivo'
                ];
            }
            //Limpiar caracteres del titulo
            $titulo = preg_replace('/[^a-zA-Z0-9áéíóúüñÁÉÍÓÚÜÑ_-]/u', '_', $titulo); //preg_replace reemplaza los caracteres no permitidos en el título por guiones bajos
            //Reemplazar espacios por guiones bajos
            $titulo = str_replace(' ', '_', $titulo); //str_replace reemplaza los espacios en el título por guiones bajos
            //Controlar el tamaño máximo permitido (5MB)
            $tamanoMaximo = 5 * 1024 * 1024;
            if ($archivo['size'] > $tamanoMaximo) {
                return [
                    'estado' => false,
                    'mensaje' => 'El archivo supera el tamaño máximo permitido (5MB)'
                ];
            }
            //Controlar las extensiones permitidas
            $extensionesPermitidas = ['pdf', 'jpg', 'png'];
            $extensionArchivo = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION)); //strtolower la convierte a minúsculas, pathinfo obtiene la extensión del archivo y PATHINFO_EXTENSION indica que queremos obtener solo la extensión del archivo
            if (!in_array($extensionArchivo, $extensionesPermitidas)) {
                return [
                    'estado' => false,
                    'mensaje' => 'La extensión del archivo no está permitida'
                ];
            }
            //Validar MIME real
            $mimesPermitidos = ['application/pdf', 'image/jpeg', 'image/png'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE); //finfo_open crea un recurso para obtener información sobre el archivo, FILEINFO_MIME_TYPE indica que queremos obtener el tipo MIME real del archivo
            $mimeArchivo = finfo_file($finfo, $archivo['tmp_name']);
            finfo_close($finfo); //finfo_close cierra el recurso creado por finfo_open
            if (!in_array($mimeArchivo, $mimesPermitidos)) {
                return [
                    'estado' => false,
                    'mensaje' => 'El tipo de archivo no es válido'
                ];
            }
            //Generar un nombre único para evitar colisiones
            $nombreUnico = uniqid('archivo_', true) . '__' . $titulo . '.' . $extensionArchivo; //uniqid genera un ID único basado en la hora actual en microsegundos, el primer parámetro es un prefijo para el ID y el segundo parámetro indica que se debe generar un ID más largo y único
            $rutaDestino = $this->directorio . $nombreUnico;
            //Mover el archivo a la carpeta de destino
            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) { //move_uploaded_file mueve el archivo desde su ubicación temporal a la ubicación final, devuelve true si se movió correctamente
                return [
                    'estado' => true,
                    'mensaje' => 'Archivo subido correctamente',
                ];
            }
            return [
                'estado' => false,
                'mensaje' => 'No fue posible guardar el archivo'
            ];
        }


        public function listar() {
            $archivos = [];
            $contenido = scandir($this->directorio); //scandir obtiene una lista de archivos y directorios dentro del directorio especificado
            foreach ($contenido as $item) {
                //Ignorar archivos y directorio ocultos
                if (str_starts_with($item, '.')) { //str_starts_with verifica si el nombre del archivo comienza con un punto (archivos ocultos)
                    continue; 
                }
                $rutaArchivo = $this->directorio . $item;
                if (is_file($rutaArchivo)) { //is_file verifica si la ruta especificada es un archivo regular
                    $partes = explode('__', pathinfo($item, PATHINFO_FILENAME), 2); //explode divide el nombre del archivo en partes utilizando "__" como delimitador, pathinfo obtiene el nombre del archivo sin la extensión y PATHINFO_FILENAME indica que queremos obtener solo el nombre del archivo sin la extensión
                    $titulo = isset($partes[1]) ? $partes[1] : pathinfo($item, PATHINFO_FILENAME); //Si el título no está presente, se utiliza el nombre del archivo sin la extensión como título
                    //Volver a poner espacios en el titulo
                    $titulo = str_replace('_', ' ', $titulo);
                    $archivos[] = [
                        'titulo' => $titulo,
                        'archivo' => $item,
                        'tamano' => filesize($rutaArchivo), //filesize obtiene el tamaño del archivo en bytes
                        'fecha' => date('Y-m-d H:i:s', filemtime($rutaArchivo)) //filemtime obtiene la fecha de última modificación del archivo, date formatea esa fecha en un formato legible
                    ];
                }
            }
            return $archivos;
        }


        public function eliminar($nombre) {
            $nombreSeguro = basename($nombre); //basename obtiene el nombre del archivo sin la ruta, evitando problemas de seguridad con rutas relativas
            $rutaArchivo = $this->directorio . $nombreSeguro;
            if (!file_exists($rutaArchivo)) { //Verificar si el archivo no existe en la ruta especificada
                return [
                    'estado' => false,
                    'mensaje' => 'Archivo no encontrado'
                ];
            }
            if (unlink($rutaArchivo)) { //unlink elimina el archivo especificado, devuelve true si se eliminó correctamente
                return [
                    'estado' => true,
                    'mensaje' => 'Archivo eliminado correctamente'
                ];
            }
            return [
                'estado' => false,
                'mensaje' => 'No fue posible eliminar el archivo'
            ];
        }
    }
?>