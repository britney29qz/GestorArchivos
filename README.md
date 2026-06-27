# Clipney

## Descripción del sistema

Clipney es una aplicación web desarrollada en **PHP** que permite gestionar archivos de forma segura mediante **Programación Orientada a Objetos (POO)**. El sistema permite subir, visualizar, descargar y eliminar archivos, empleando medidas de seguridad para prevenir riesgos o vulnerabilidad asociadas al manejo de archivos en la web.

La aplicación acepta únicamente archivos en formato **PDF**, **JPG** y **PNG**, valida el tipo MIME y la extensión del archivo, limita el tamaño permitido, asigna un nombre único para evitar colisiones y protege el directorio de almacenamiento contra accesos no autorizados.

---

## Tecnologías utilizadas

- PHP
- HTML5
- Tailwind CSS
- Programación Orientada a Objetos (POO)
- XAMPP (Apache)

---

## Instrucciones de uso

1. Ejecutar Apache desde XAMPP.
2. Copiar el proyecto dentro de la carpeta `htdocs`.
3. Acceder desde el navegador mediante la dirección:

```
http://localhost/webArchivos/
```

4. Seleccionar un archivo en formato **PDF**, **JPG** o **PNG**.
5. Escribir el nombre o título del archivo.
6. Presionar el botón **Subir archivo**.
7. Visualizar los archivos almacenados en la tabla principal.
8. Descargar o eliminar archivos utilizando los botones correspondientes.

---

## Explicación de la clase `GestorArchivos`

La clase `GestorArchivos` concentra toda la lógica relacionada con la administración de archivos, siguiendo los principios de la Programación Orientada a Objetos.

### Método `subir($archivo, $titulo)`

Este método se encarga de:

- Validar la extensión del archivo.
- Verificar el tipo MIME.
- Comprobar el tamaño máximo permitido.
- Limpiar el título ingresado por el usuario.
- Generar un nombre único para evitar archivos duplicados.
- Guardar el archivo en la carpeta `uploads`.

### Método `listar()`

Este método permite:

- Obtener todos los archivos almacenados.
- Ignorar archivos ocultos y de configuración.
- Obtener el título, nombre físico, tamaño y fecha de carga.
- Devolver la información para mostrarla en la tabla principal.

### Método `eliminar($archivo)`

Este método realiza las siguientes acciones:

- Valida el nombre del archivo recibido.
- Evita ataques de *Path Traversal* mediante `basename()`.
- Comprueba que el archivo exista.
- Elimina el archivo de forma segura.

---

## Medidas de seguridad implementadas

Durante el desarrollo del sistema se aplicaron las siguientes medidas de seguridad:

- Validación de la extensión del archivo.
- Validación del tipo MIME.
- Límite del tamaño máximo permitido.
- Renombrado automático mediante un identificador único para evitar colisiones.
- Conservación del nombre asignado por el usuario.
- Limpieza del título mediante `trim()` y `strip_tags()`.
- Protección frente a ataques de *Path Traversal* utilizando `basename()`.
- Uso de `htmlspecialchars()` para evitar la ejecución de código HTML al mostrar información.
- Protección de la carpeta `uploads` mediante un archivo `.htaccess` para impedir el listado del contenido.
- Filtrado de archivos ocultos (como `.htaccess`) durante el listado de archivos.

---
