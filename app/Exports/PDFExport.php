<?php

namespace App\Exports;

use PDF;
// use App\Models\{LineUpContract};

class PDFExport
{
    public function __construct($data, $filename, $type){
        $this->data = $data;
        $this->filename = $filename;
        $this->type = $type;
    }

    public function download(){
        $pdf = PDF::loadView('exports.' . $this->type, ['data' => $this->data]);
        $pdf->setPaper('a4', 'Portrait');
        return $pdf->download($this->filename . '.pdf');
    }
}
