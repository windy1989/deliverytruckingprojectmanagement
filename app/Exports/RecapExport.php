<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMapping;

class RecapExport implements FromView
{

    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
        return view('print.recap', $this->data);
    }
}
