<?php

namespace App\Exports;

use PDF;
use App\Models\Setting;
// use App\Models\{LineUpContract};

class PDFExport
{
    public function __construct($data, $filename, $type){
        $this->data = $data;
        $this->filename = $filename;
        $this->type = $type;
    }

    public function download(){
        $settings = Setting::pluck('value', 'name');

        $pdf = PDF::loadView('exports.' . $this->type, ['data' => $this->data, 'settings' => $settings]);
        $pdf->setPaper('a4', 'Portrait');
        return $pdf->download($this->filename . '.pdf');
    }
}
