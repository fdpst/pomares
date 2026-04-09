<?php

namespace App\Imports;

use App\Models\CategoriaCuentaContable;
use Maatwebsite\Excel\Concerns\ToModel;

class CategoriaCuentaContableImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Verificar si la fila tiene datos en las columnas relevantes
        if (!empty($row[1]) && !empty($row[2])) {
            return new CategoriaCuentaContable([
                'cuenta' => $row[1], // Columna B
                'denominacion' => $row[2], // Columna C
            ]);
        }
        
        // Si la fila no contiene datos relevantes, devolver null para omitirla
        return null;
    }

    public function startRow(): int {
        return 4;
    }
}
