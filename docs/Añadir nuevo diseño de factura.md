# Cómo añadir un nuevo diseño de factura/albarán

Esta guía explica paso a paso cómo añadir un nuevo diseño de factura o albarán al sistema. **El sistema ahora es dinámico**, lo que significa que detecta automáticamente los templates disponibles sin necesidad de modificar código en múltiples lugares.

## Sistema Dinámico de Templates

El sistema utiliza `AlbaranTemplateHelper` que escanea automáticamente la carpeta `resources/views/pdf/` para detectar templates de albaranes. Los templates se detectan por patrón de nombre de archivo.

## Paso 1: Crear la nueva vista Blade

Crea un nuevo archivo en `resources/views/pdf/` siguiendo uno de estos patrones de nombre:

- `albaran-{nombre}.blade.php` (recomendado)
- `albaran{nombre}.blade.php`

**Ejemplo:** `resources/views/pdf/albaran-elegante.blade.php`

### Convenciones de nombres

- El nombre debe ser en minúsculas
- Puedes usar guiones (`-`) o guiones bajos (`_`) para separar palabras
- El sistema detectará automáticamente el template y lo mostrará en el selector

### Variables disponibles

Este archivo debe recibir las siguientes variables:

- `$data`: Colección de servicios/items (con `descripcion`, `cantidad`, `precio`, `importe`)
- `$userLog`: Objeto del usuario/empresa (con `nombre_fiscal`, `direccion`, `ciudad`, `provincia`, `cif`, `telefono`, `email`, `logo_base64`, `avatar`)
- `$total`: Total del documento
- `$nro_factura`: Número de factura/albarán
- `$fecha_emision`: Fecha de emisión (formato: d/m/Y)
- `$cliente`: Objeto del cliente (con `nombre`, `dni`, `telefono`, `direccion`, `codigo_postal`, `localidad`, `provincia->nombre`, `pais->nombre`)

**Nota importante:** El template `classic` usa `new-recibo.blade.php` y recibe un objeto `$recibo` completo en lugar de `$data`. Si tu template sigue el patrón `albaran-{nombre}.blade.php`, recibirá las variables listadas arriba.

### Ejemplo de estructura básica:

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        /* Tus estilos CSS aquí */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ALBARÁN #{{ str_pad($nro_factura, 4, '0', STR_PAD_LEFT) }}</h1>
        <p>Fecha: {{ $fecha_emision }}</p>
    </div>
    
    <div class="company">
        @if($userLog->logo_base64)
            <img src="data:image/png;base64,{{ $userLog->logo_base64 }}" alt="Logo" style="max-width: 200px;">
        @endif
        <strong>{{ $userLog->nombre_fiscal ?? $userLog->nombre }}</strong><br>
        {{ $userLog->direccion ?? '' }}<br>
        {{ $userLog->codigo_postal ?? '' }} {{ $userLog->ciudad ?? '' }}<br>
        CIF: {{ $userLog->cif ?? '' }}
    </div>
    
    <div class="client">
        <strong>Cliente:</strong> {{ $cliente->nombre ?? '' }}<br>
        CIF/NIF: {{ $cliente->dni ?? '' }}<br>
        {{ $cliente->direccion ?? '' }}<br>
        {{ $cliente->codigo_postal ?? '' }} {{ $cliente->localidad ?? '' }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $servicio)
            <tr>
                <td>{{ $servicio->descripcion }}</td>
                <td>{{ $servicio->cantidad }}</td>
                <td>{{ number_format($servicio->precio, 2, ',', '.') }} €</td>
                <td>{{ number_format($servicio->importe, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="total">
        <strong>TOTAL: {{ number_format($total, 2, ',', '.') }} €</strong>
    </div>
</body>
</html>
```

## Paso 2: Actualizar el Frontend (Opcional)

### 2.1 Añadir imagen de preview (Opcional)

Si quieres mostrar una imagen de preview en el selector de templates:

1. Crea una imagen de preview en `src/assets/images/templates/`
2. Nombra el archivo: `{nombre-del-template}-preview.png`
3. Actualiza el mapeo en `src/pages/system-params/index.vue`:

```javascript
const previewMap = {
    'classic': classicPreview,
    'modern': modernPreview,
    'elegante': elegantePreview, // ← Añade tu nuevo template
};
```

**Nota:** Si no añades la imagen, el sistema mostrará un placeholder automáticamente.

## Paso 3: Probar el nuevo diseño

1. **Recarga la página** de configuración del sistema (`/system-params`)
2. **Verifica que el nuevo template aparece** en el selector de "Diseño de albarán"
3. **Selecciona el nuevo diseño** y guárdalo
4. **Genera un nuevo albarán** desde cualquiera de los formularios
5. **Verifica que el PDF se genera correctamente** con el nuevo diseño

## Cómo funciona el sistema dinámico

### Detección automática

El sistema utiliza `AlbaranTemplateHelper::getAvailableTemplates()` que:

1. Escanea la carpeta `resources/views/pdf/`
2. Detecta archivos que coincidan con el patrón `albaran[-_]{nombre}.blade.php`
3. Normaliza los nombres (ej: `albaranEnviado.blade.php` → `simple`)
4. Devuelve la lista de templates disponibles

### Normalización de templates

El helper normaliza automáticamente algunos nombres conocidos:

- `albaranEnviado.blade.php` → `simple`
- `albaran-moderno.blade.php` → `modern`
- `albaran-{cualquier-otro}.blade.php` → `{cualquier-otro}`

### Uso en controladores

Todos los controladores (`AlbaranController`, `ReciboController`, `SystemParamController`) utilizan el helper para:

- Normalizar nombres de templates: `AlbaranTemplateHelper::normalizeTemplate($template)`
- Obtener el nombre de la vista: `AlbaranTemplateHelper::getViewName($templateKey)`
- Validar que el template existe: `AlbaranTemplateHelper::templateExists($template)`

## Notas importantes

### DomPDF limitations

DomPDF tiene limitaciones con CSS moderno. Recomendaciones:

- Usa tablas HTML en lugar de flexbox/grid cuando sea posible
- Evita CSS Grid y Flexbox complejos
- Usa posicionamiento absoluto con cuidado
- Prueba siempre el PDF generado

### Logo

- **Para PDFs**: El logo se pasa como `logo_base64` (string base64)
- **Para previews HTML**: El logo se pasa como `avatar` (ruta del archivo)

### Formato de números

Usa `number_format($valor, 2, ',', '.')` para formatear números con formato español:

```blade
{{ number_format($total, 2, ',', '.') }} €
```

### Fechas

La fecha viene formateada como `d/m/Y` desde el controlador (ej: `15/12/2024`).

### Templates especiales

- **`classic`**: Usa `pdf.new-recibo` y recibe un objeto `$recibo` completo. Este es el template por defecto.
- **Otros templates**: Usan el patrón `pdf.albaran-{nombre}` y reciben las variables individuales (`$data`, `$userLog`, `$total`, etc.)

## Ejemplo completo de un diseño simple

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #4A5568;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #1E40AF;
        }
        .company-info, .client-info {
            margin: 20px 0;
            padding: 15px;
            background: #F7FAFC;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background: #4A5568;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #E2E8F0;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
            padding: 15px;
            background: #EDF2F7;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ALBARÁN #{{ str_pad($nro_factura, 4, '0', STR_PAD_LEFT) }}</h1>
        <p>Fecha de emisión: {{ $fecha_emision }}</p>
    </div>
    
    <div class="company-info">
        @if($userLog->logo_base64)
            <img src="data:image/png;base64,{{ $userLog->logo_base64 }}" alt="Logo" style="max-width: 150px; margin-bottom: 10px;">
        @endif
        <strong>{{ $userLog->nombre_fiscal ?? $userLog->nombre }}</strong><br>
        {{ $userLog->direccion ?? '' }}<br>
        {{ $userLog->codigo_postal ?? '' }} {{ $userLog->ciudad ?? '' }}<br>
        @if($userLog->provincia)
            {{ $userLog->provincia->nombre ?? '' }}
        @endif<br>
        CIF: {{ $userLog->cif ?? '' }}<br>
        @if($userLog->telefono)
            Tel: {{ $userLog->telefono }}<br>
        @endif
        @if($userLog->email)
            Email: {{ $userLog->email }}
        @endif
    </div>
    
    <div class="client-info">
        <strong>DATOS DEL CLIENTE</strong><br>
        <strong>Nombre:</strong> {{ $cliente->nombre ?? '' }}<br>
        <strong>CIF/NIF:</strong> {{ $cliente->dni ?? '' }}<br>
        <strong>Dirección:</strong> {{ $cliente->direccion ?? '' }}<br>
        <strong>Código Postal:</strong> {{ $cliente->codigo_postal ?? '' }}<br>
        <strong>Localidad:</strong> {{ $cliente->localidad ?? '' }}<br>
        @if($cliente->provincia)
            <strong>Provincia:</strong> {{ $cliente->provincia->nombre ?? '' }}<br>
        @endif
        @if($cliente->pais)
            <strong>País:</strong> {{ $cliente->pais->nombre ?? '' }}
        @endif
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $servicio)
            <tr>
                <td>{{ $servicio->descripcion ?? '' }}</td>
                <td>{{ $servicio->cantidad ?? 0 }}</td>
                <td>{{ number_format($servicio->precio ?? 0, 2, ',', '.') }} €</td>
                <td>{{ number_format($servicio->importe ?? 0, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="total">
        TOTAL: {{ number_format($total, 2, ',', '.') }} €
    </div>
</body>
</html>
```

## Resumen

Para añadir un nuevo diseño:

1. ✅ Crea el archivo Blade en `resources/views/pdf/albaran-{nombre}.blade.php`
2. ✅ El sistema lo detectará automáticamente
3. ✅ (Opcional) Añade una imagen de preview en `src/assets/images/templates/`
4. ✅ Prueba el nuevo diseño

**¡Ya no necesitas modificar código en múltiples controladores!** El sistema es completamente dinámico.
