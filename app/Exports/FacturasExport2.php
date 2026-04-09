<?php

namespace App\Exports;

use App\Models\recibo;
use Maatwebsite\Excel\Concerns\FromCollection;

class FacturasExport2 implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return recibo::all();
    }
}
