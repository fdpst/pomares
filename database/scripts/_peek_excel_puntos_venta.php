<?php

/**
 * Uso (desde raíz del proyecto): php database/scripts/_peek_excel_puntos_venta.php "ruta/al.xlsx"
 * Solo inspección; borrar cuando no haga falta.
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;

class PeekImport implements ToCollection
{
    public Collection $rows;

    public function collection(Collection $collection): void
    {
        $this->rows = $collection;
    }
}

$path = $argv[1] ?? 'C:/Users/Luis/Downloads/DATOS PUNTOS DE VENTA.xlsx';
if (! is_readable($path)) {
    fwrite(STDERR, "No se puede leer: {$path}\n");
    exit(1);
}

$peek = new PeekImport();
Excel::import($peek, $path);
foreach ($peek->rows->take(8) as $i => $row) {
    echo $i . ': ' . json_encode($row->toArray(), JSON_UNESCAPED_UNICODE) . PHP_EOL;
}
