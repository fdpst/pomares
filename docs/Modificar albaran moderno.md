Cómo modificar el PDF moderno
1) Edita la plantilla resources/views/pdf/albaran-moderno.blade.php.
Estilos CSS en el <style> del <head>.
Estructura HTML: bloques .header, .meta, .client, tabla y .totals.
Datos disponibles:
$userLog (empresa: nombre_fiscal, direccion, provincia, telefono, email, logo ya resuelto).
$cliente (nombre, dni, telefono, direccion, codigo_postal, localidad, provincia->nombre, pais->nombre).
$data (colección de líneas con descripcion, cantidad, precio, importe).
$total, $nro_factura, $fecha_emision.
2) Para cambiar columnas o formato:
Ajusta los <th>/<td> de la tabla.
Cambia los number_format si necesitas otro formato.
3) Para añadir totales adicionales (IVA, descuentos):
Añade más filas en .totals usando los valores que quieras calcular en el controlador antes de pasar a la vista.
4) Para mover u ocultar datos:
Quita/edita las filas en .meta o .client.
5) Si quieres usar otro logo/campo:
El logo llega en $userLog->logo. Cambia el <img> o añade condiciones.
6) Si quieres otro nombre de plantilla:
Añade un nuevo identificador en frontend (listas de templates) y en normalizeTemplate del controlador, y crea otra vista en resources/views/pdf/.
