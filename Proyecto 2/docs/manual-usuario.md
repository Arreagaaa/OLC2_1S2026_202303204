# Manual de Usuario

## 1. Requisitos
- PHP 8.1 o superior.
- Composer instalado.
- Servidor local con acceso al navegador.

## 2. Instalacion
1. Abrir una terminal en la carpeta `Proyecto 2`.
2. Ejecutar `composer install` si todavia no estan las dependencias.
3. Ejecutar `bash build.sh` solo si cambiaste la gramatica ANTLR.

## 3. Ejecucion local
1. Iniciar el servidor PHP:
   ```bash
   php -S localhost:8000 -t .
   ```
2. Abrir en el navegador:
   ```
   http://localhost:8000/frontend/index.html
   ```

## 4. Uso de la GUI
1. Escribir codigo Golampi en el editor.
2. Presionar **Compilar**.
3. La consola muestra el ARM64 generado o los errores encontrados.
4. Usar **Tabla de simbolos** y **Tabla de errores** para ver los reportes.
5. Usar **Descargar ARM64** para guardar el archivo `.s` generado.
6. Usar **Descargar simbolos** y **Descargar errores** para exportar reportes HTML.

## 5. Flujo de trabajo recomendado
1. Probar primero un programa pequeno.
2. Revisar que no existan errores semanticos.
3. Compilar y verificar el ARM64 generado.
4. Correr los archivos de prueba en `test/test1/` para validar cambios.

## 6. Problemas comunes
- Si el navegador no carga, verificar que el servidor PHP siga corriendo.
- Si la consola muestra errores, revisar lineas y columnas del reporte.
- Si cambiaste la gramatica, volver a regenerar los archivos ANTLR.