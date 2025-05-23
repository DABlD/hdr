<?php

namespace App\Exports;

use PDF;
use App\Models\Setting;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class PDFExport
{
    public function __construct($data, $filename, $type){
        $this->data = $data;
        $this->filename = $filename;
        $this->type = $type;
    }

    public function report(){
        $settings = Setting::where('clinic', env('CLINIC'))->pluck('value', 'name');

        // CREATE TEMP IF NOT EXISTS;
        $path = "uploads/temp";
        is_dir($path) ? true : mkdir($path);

        $oMerger = PDFMerger::init();

        $path = "uploads/temp/$this->filename.pdf";
        $pdf = PDF::loadView('exports.' . $this->type, ['data' => $this->data, 'settings' => $settings]);
        $pdf->setPaper('a4', 'Portrait');
        $pdf->setWarnings(false)->save($path);
        $oMerger->addPDF($path);

        if(is_array($this->data->file)){
            $files = json_decode($this->data->file);
            foreach ($files as $file) {
                $oMerger->addPDF(public_path(env('UPLOAD_URL') . $file));
            }
        }
        // else{
        //     $oMerger->addPDF(public_path($this->data->file));
        // }

        $oMerger->merge();
        $oMerger->setFileName($this->filename . '.pdf');
        $oMerger->stream();
    }

    public function invoice(){
        $settings = Setting::where('clinic', env('CLINIC'))->pluck('value', 'name');

        // CREATE TEMP IF NOT EXISTS;
        // $path = "uploads/invoice ";
        // is_dir($path) ? true : mkdir($path);

        // $path = "uploads/invoice/$this->filename.pdf";
        $pdf = PDF::loadView('exports.' . $this->type, ['data' => $this->data, 'settings' => $settings]);
        $pdf->setPaper('a4', 'Portrait');
        return $pdf->stream($this->filename . '.pdf');
    }
}